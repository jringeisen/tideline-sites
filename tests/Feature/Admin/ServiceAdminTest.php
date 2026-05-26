<?php

use App\Models\Location;
use App\Models\Service;
use App\Models\User;

beforeEach(function () {
    config()->set('admin.emails', ['admin@example.com']);
    $this->admin = User::factory()->create([
        'email' => 'admin@example.com',
        'email_verified_at' => now(),
    ]);
});

test('guests are redirected from admin services', function () {
    $this->get('/admin/services')->assertRedirect();
});

test('non-admin authenticated users get 403 on admin services', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)->get('/admin/services')->assertForbidden();
});

test('admin can list services with location counts', function () {
    $service = Service::factory()->create();
    $service->locations()->sync(Location::factory()->count(2)->create()->pluck('id'));

    $response = $this->actingAs($this->admin)->get('/admin/services')->assertOk();

    $rows = $response->viewData('page')['props']['services'];
    expect(collect($rows)->firstWhere('id', $service->id)['locations_count'])->toBe(2);
});

test('admin can view the create form', function () {
    $this->actingAs($this->admin)->get('/admin/services/create')->assertOk();
});

test('admin can store a service with auto-slug, faqs, and synced locations', function () {
    $locations = Location::factory()->count(2)->create();

    $this->actingAs($this->admin)
        ->post('/admin/services', [
            'name' => 'Web Design',
            'summary' => 'Fast sites.',
            'body' => '<p>In depth.</p>',
            'faqs' => [['question' => 'How much?', 'answer' => 'From $299.']],
            'is_published' => true,
            'sort_order' => 3,
            'locations' => $locations->pluck('id')->all(),
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $service = Service::query()->where('name', 'Web Design')->firstOrFail();
    expect($service->slug)->toBe('web-design')
        ->and($service->summary)->toBe('Fast sites.')
        ->and($service->faqs[0]['question'])->toBe('How much?')
        ->and($service->sort_order)->toBe(3)
        ->and($service->locations->pluck('id')->all())->toEqualCanonicalizing($locations->pluck('id')->all());
});

test('storing a service requires a name', function () {
    $this->actingAs($this->admin)
        ->post('/admin/services', ['summary' => 'No name.'])
        ->assertSessionHasErrors('name');

    expect(Service::count())->toBe(0);
});

test('an explicit service slug must be unique', function () {
    Service::factory()->create(['slug' => 'taken']);

    $this->actingAs($this->admin)
        ->post('/admin/services', ['name' => 'Another', 'slug' => 'taken'])
        ->assertSessionHasErrors('slug');
});

test('admin can update a service and resync locations', function () {
    $service = Service::factory()->create(['name' => 'Old']);
    $service->locations()->sync(Location::factory()->create()->id);
    $newLocation = Location::factory()->create();

    $this->actingAs($this->admin)
        ->patch("/admin/services/{$service->id}", [
            'name' => 'SEO Optimization',
            'is_published' => true,
            'locations' => [$newLocation->id],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $service->refresh()->load('locations');
    expect($service->name)->toBe('SEO Optimization')
        ->and($service->locations->pluck('id')->all())->toBe([$newLocation->id]);
});

test('admin can delete a service', function () {
    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->delete("/admin/services/{$service->id}")
        ->assertRedirect(route('admin.services.index'));

    expect(Service::find($service->id))->toBeNull();
});

test('non-admin cannot mutate services', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $service = Service::factory()->create();

    $this->actingAs($user)->post('/admin/services', ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->patch("/admin/services/{$service->id}", ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/services/{$service->id}")->assertForbidden();

    expect(Service::whereKey($service->id)->exists())->toBeTrue();
});
