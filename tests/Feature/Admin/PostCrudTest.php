<?php

use App\Enums\PostStatus;
use App\Models\Category;
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

test('non-admin authenticated users get 403 on admin posts index', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get('/admin/posts')
        ->assertForbidden();
});

test('guests are redirected from admin posts', function () {
    $this->get('/admin/posts')->assertRedirect();
});

test('admin can list posts', function () {
    Post::factory()->count(3)->for($this->admin, 'author')->create();

    $this->actingAs($this->admin)
        ->get('/admin/posts')
        ->assertOk();
});

test('admin can store a post with tags and auto-slug', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();

    $response = $this->actingAs($this->admin)->post('/admin/posts', [
        'title' => 'My First Post',
        'excerpt' => 'Hello world.',
        'content' => '<p>'.str_repeat('word ', 250).'</p>',
        'category_id' => $category->id,
        'status' => 'published',
        'published_at' => now()->toDateTimeString(),
        'tags' => [$tag->id],
    ]);

    $response->assertRedirect();

    $post = Post::query()->where('title', 'My First Post')->firstOrFail();
    expect($post->slug)->toBe('my-first-post')
        ->and($post->author_id)->toBe($this->admin->id)
        ->and($post->excerpt)->toBe('Hello world.')
        ->and($post->content)->toContain('<p>')
        ->and($post->content)->toContain('word')
        ->and($post->reading_time_minutes)->toBeGreaterThan(0)
        ->and($post->tags->pluck('id')->all())->toBe([$tag->id]);
});

test('admin can update a post and resync tags', function () {
    $post = Post::factory()->for($this->admin, 'author')->create(['title' => 'Old']);
    $newTag = Tag::factory()->create();

    $this->actingAs($this->admin)->patch("/admin/posts/{$post->id}", [
        'title' => 'New Title',
        'content' => '<p>Updated.</p>',
        'status' => 'draft',
        'published_at' => null,
        'tags' => [$newTag->id],
    ])->assertRedirect();

    $post->refresh()->load('tags');
    expect($post->title)->toBe('New Title')
        ->and($post->status)->toBe(PostStatus::Draft)
        ->and($post->content)->toBe('<p>Updated.</p>')
        ->and($post->tags->pluck('id')->all())->toBe([$newTag->id]);
});

test('admin can delete a post', function () {
    $post = Post::factory()->for($this->admin, 'author')->create();

    $this->actingAs($this->admin)
        ->delete("/admin/posts/{$post->id}")
        ->assertRedirect();

    expect(Post::find($post->id))->toBeNull();
});

test('post validation rejects missing title and content', function () {
    $this->actingAs($this->admin)
        ->post('/admin/posts', ['status' => 'draft'])
        ->assertSessionHasErrors(['title', 'content']);
});
