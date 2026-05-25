<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    config()->set('admin.emails', ['admin@example.com']);
    $this->admin = User::factory()->create([
        'email' => 'admin@example.com',
        'email_verified_at' => now(),
    ]);
});

test('guests are redirected from admin categories', function () {
    $this->get('/admin/categories')->assertRedirect();
});

test('non-admin authenticated users get 403 on admin categories', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get('/admin/categories')
        ->assertForbidden();
});

test('admin can list categories with post counts', function () {
    $category = Category::factory()->create();
    Post::factory()->count(2)->for($this->admin, 'author')->create(['category_id' => $category->id]);

    $response = $this->actingAs($this->admin)
        ->get('/admin/categories')
        ->assertOk();

    $rows = $response->viewData('page')['props']['categories'];
    expect(collect($rows)->firstWhere('id', $category->id)['posts_count'])->toBe(2);
});

test('admin can view the create form', function () {
    $this->actingAs($this->admin)
        ->get('/admin/categories/create')
        ->assertOk();
});

test('admin can store a category with an auto-generated slug', function () {
    $this->actingAs($this->admin)
        ->post('/admin/categories', [
            'name' => 'Coastal Living',
            'description' => 'Life by the sea.',
        ])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status');

    $category = Category::query()->where('name', 'Coastal Living')->firstOrFail();
    expect($category->slug)->toBe('coastal-living')
        ->and($category->description)->toBe('Life by the sea.');
});

test('admin can store a category with an explicit slug', function () {
    $this->actingAs($this->admin)
        ->post('/admin/categories', [
            'name' => 'Coastal Living',
            'slug' => 'seaside',
        ])
        ->assertRedirect(route('admin.categories.index'));

    expect(Category::query()->where('slug', 'seaside')->exists())->toBeTrue();
});

test('duplicate names produce unique slugs', function () {
    $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'Beach Tips']);
    $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'Beach Tips']);

    $slugs = Category::query()->where('name', 'Beach Tips')->orderBy('id')->pluck('slug')->all();
    expect($slugs)->toBe(['beach-tips', 'beach-tips-2']);
});

test('storing a category requires a name', function () {
    $this->actingAs($this->admin)
        ->post('/admin/categories', ['description' => 'No name here.'])
        ->assertSessionHasErrors('name');

    expect(Category::count())->toBe(0);
});

test('an explicit slug must be unique', function () {
    Category::factory()->create(['slug' => 'taken']);

    $this->actingAs($this->admin)
        ->post('/admin/categories', ['name' => 'Another', 'slug' => 'taken'])
        ->assertSessionHasErrors('slug');
});

test('admin can view the edit form', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->get("/admin/categories/{$category->id}/edit")
        ->assertOk();
});

test('admin can update a category', function () {
    $category = Category::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

    $this->actingAs($this->admin)
        ->patch("/admin/categories/{$category->id}", [
            'name' => 'New Name',
            'slug' => 'new-name',
            'description' => 'Updated description.',
        ])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status');

    $category->refresh();
    expect($category->name)->toBe('New Name')
        ->and($category->slug)->toBe('new-name')
        ->and($category->description)->toBe('Updated description.');
});

test('updating a category may keep its own slug', function () {
    $category = Category::factory()->create(['slug' => 'keepme']);

    $this->actingAs($this->admin)
        ->patch("/admin/categories/{$category->id}", [
            'name' => 'Renamed',
            'slug' => 'keepme',
        ])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHasNoErrors();
});

test('updating to a slug used by another category fails validation', function () {
    Category::factory()->create(['slug' => 'taken']);
    $category = Category::factory()->create(['slug' => 'mine']);

    $this->actingAs($this->admin)
        ->patch("/admin/categories/{$category->id}", ['name' => 'Mine', 'slug' => 'taken'])
        ->assertSessionHasErrors('slug');
});

test('admin can delete a category', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->delete("/admin/categories/{$category->id}")
        ->assertRedirect(route('admin.categories.index'));

    expect(Category::find($category->id))->toBeNull();
});

test('non-admin cannot store, update, or delete categories', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $category = Category::factory()->create();

    $this->actingAs($user)->post('/admin/categories', ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->patch("/admin/categories/{$category->id}", ['name' => 'Nope'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/categories/{$category->id}")->assertForbidden();

    expect(Category::whereKey($category->id)->exists())->toBeTrue();
});
