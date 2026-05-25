<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    config()->set('admin.emails', ['admin@example.com']);
    $this->admin = User::factory()->create([
        'email' => 'admin@example.com',
        'email_verified_at' => now(),
    ]);
});

test('guests are redirected from admin tags', function () {
    $this->get('/admin/tags')->assertRedirect();
});

test('non-admin authenticated users get 403 on admin tags', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get('/admin/tags')
        ->assertForbidden();
});

test('admin can list tags with post counts', function () {
    $tag = Tag::factory()->create();
    $posts = Post::factory()->count(2)->for($this->admin, 'author')->create();
    $tag->posts()->sync($posts->pluck('id'));

    $response = $this->actingAs($this->admin)
        ->get('/admin/tags')
        ->assertOk();

    $rows = $response->viewData('page')['props']['tags'];
    expect(collect($rows)->firstWhere('id', $tag->id)['posts_count'])->toBe(2);
});

test('admin can view the create form', function () {
    $this->actingAs($this->admin)
        ->get('/admin/tags/create')
        ->assertOk();
});

test('admin can store a tag with an auto-generated slug', function () {
    $this->actingAs($this->admin)
        ->post('/admin/tags', ['name' => 'Local SEO'])
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('status');

    $tag = Tag::query()->where('name', 'Local SEO')->firstOrFail();
    expect($tag->slug)->toBe('local-seo');
});

test('admin can store a tag with an explicit slug', function () {
    $this->actingAs($this->admin)
        ->post('/admin/tags', ['name' => 'Local SEO', 'slug' => 'seo'])
        ->assertRedirect(route('admin.tags.index'));

    expect(Tag::query()->where('slug', 'seo')->exists())->toBeTrue();
});

test('duplicate names produce unique slugs', function () {
    $this->actingAs($this->admin)->post('/admin/tags', ['name' => 'Web Design']);
    $this->actingAs($this->admin)->post('/admin/tags', ['name' => 'Web Design']);

    $slugs = Tag::query()->where('name', 'Web Design')->orderBy('id')->pluck('slug')->all();
    expect($slugs)->toBe(['web-design', 'web-design-2']);
});

test('storing a tag requires a name', function () {
    $this->actingAs($this->admin)
        ->post('/admin/tags', [])
        ->assertSessionHasErrors('name');

    expect(Tag::count())->toBe(0);
});

test('an explicit slug must be unique', function () {
    Tag::factory()->create(['slug' => 'taken']);

    $this->actingAs($this->admin)
        ->post('/admin/tags', ['name' => 'Another', 'slug' => 'taken'])
        ->assertSessionHasErrors('slug');
});

test('admin can view the edit form', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->admin)
        ->get("/admin/tags/{$tag->id}/edit")
        ->assertOk();
});

test('admin can update a tag', function () {
    $tag = Tag::factory()->create(['name' => 'Old', 'slug' => 'old']);

    $this->actingAs($this->admin)
        ->patch("/admin/tags/{$tag->id}", ['name' => 'New', 'slug' => 'new'])
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('status');

    $tag->refresh();
    expect($tag->name)->toBe('New')
        ->and($tag->slug)->toBe('new');
});

test('updating a tag may keep its own slug', function () {
    $tag = Tag::factory()->create(['slug' => 'keepme']);

    $this->actingAs($this->admin)
        ->patch("/admin/tags/{$tag->id}", ['name' => 'Renamed', 'slug' => 'keepme'])
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHasNoErrors();
});

test('updating to a slug used by another tag fails validation', function () {
    Tag::factory()->create(['slug' => 'taken']);
    $tag = Tag::factory()->create(['slug' => 'mine']);

    $this->actingAs($this->admin)
        ->patch("/admin/tags/{$tag->id}", ['name' => 'Mine', 'slug' => 'taken'])
        ->assertSessionHasErrors('slug');
});

test('admin can delete a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->admin)
        ->delete("/admin/tags/{$tag->id}")
        ->assertRedirect(route('admin.tags.index'));

    expect(Tag::find($tag->id))->toBeNull();
});

test('non-admin cannot store, update, or delete tags', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $tag = Tag::factory()->create();

    $this->actingAs($user)->post('/admin/tags', ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->patch("/admin/tags/{$tag->id}", ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/tags/{$tag->id}")->assertForbidden();

    expect(Tag::whereKey($tag->id)->exists())->toBeTrue();
});
