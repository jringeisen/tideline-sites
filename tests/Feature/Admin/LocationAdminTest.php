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

test('guests are redirected from admin locations', function () {
    $this->get('/admin/locations')->assertRedirect();
});

test('non-admin authenticated users get 403 on admin locations', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)->get('/admin/locations')->assertForbidden();
});

test('admin can list locations', function () {
    Location::factory()->count(3)->create();

    $this->actingAs($this->admin)->get('/admin/locations')->assertOk();
});

test('admin can view the create form', function () {
    $this->actingAs($this->admin)->get('/admin/locations/create')->assertOk();
});

test('admin can store a location with auto-slug, segments, faqs, and synced relations', function () {
    $nearby = Location::factory()->create(['name' => 'Destin']);
    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->post('/admin/locations', [
            'name' => 'Panama City Beach',
            'display_name' => 'Panama City Beach, FL',
            'region' => 'Bay County',
            'intro' => 'Busy tourist market.',
            'segments' => [['title' => 'Rentals', 'body' => 'Property sites.']],
            'faqs' => [['question' => 'Do you serve PCB?', 'answer' => 'Yes.']],
            'lat' => 30.1766,
            'lng' => -85.8055,
            'is_published' => true,
            'nearby' => [$nearby->id],
            'services' => [$service->id],
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $location = Location::query()->where('name', 'Panama City Beach')->firstOrFail();
    expect($location->slug)->toBe('panama-city-beach')
        ->and($location->display_name)->toBe('Panama City Beach, FL')
        ->and($location->segments[0]['title'])->toBe('Rentals')
        ->and($location->faqs[0]['question'])->toBe('Do you serve PCB?')
        ->and($location->lat)->toBe(30.1766)
        ->and($location->nearby->pluck('id')->all())->toBe([$nearby->id])
        ->and($location->services->pluck('id')->all())->toBe([$service->id]);
});

test('storing a location requires a name and display name', function () {
    $this->actingAs($this->admin)
        ->post('/admin/locations', ['intro' => 'No names.'])
        ->assertSessionHasErrors(['name', 'display_name']);

    expect(Location::count())->toBe(0);
});

test('an explicit location slug must be unique', function () {
    Location::factory()->create(['slug' => 'taken']);

    $this->actingAs($this->admin)
        ->post('/admin/locations', [
            'name' => 'Another',
            'display_name' => 'Another, FL',
            'slug' => 'taken',
        ])
        ->assertSessionHasErrors('slug');
});

test('admin can update a location and resync relations', function () {
    $location = Location::factory()->create(['name' => 'Old']);
    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->patch("/admin/locations/{$location->id}", [
            'name' => 'Seaside',
            'display_name' => 'Seaside, FL',
            'is_published' => true,
            'services' => [$service->id],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $location->refresh()->load('services');
    expect($location->name)->toBe('Seaside')
        ->and($location->services->pluck('id')->all())->toBe([$service->id]);
});

test('admin can delete a location', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->delete("/admin/locations/{$location->id}")
        ->assertRedirect(route('admin.locations.index'));

    expect(Location::find($location->id))->toBeNull();
});

test('non-admin cannot mutate locations', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $location = Location::factory()->create();

    $this->actingAs($user)->post('/admin/locations', ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->patch("/admin/locations/{$location->id}", ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/locations/{$location->id}")->assertForbidden();

    expect(Location::whereKey($location->id)->exists())->toBeTrue();
});
