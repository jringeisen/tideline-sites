<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

it('derives a slug from the source attribute when none is given', function () {
    expect(Category::factory()->create(['name' => 'Web Design', 'slug' => null])->slug)->toBe('web-design');
    expect(Tag::factory()->create(['name' => 'SEO Tips', 'slug' => null])->slug)->toBe('seo-tips');
    expect(Post::factory()->create(['title' => 'Hello World', 'slug' => null])->slug)->toBe('hello-world');
});

it('appends an incrementing suffix when slugs collide', function () {
    Category::factory()->create(['name' => 'News', 'slug' => null]);
    $second = Category::factory()->create(['name' => 'News', 'slug' => null]);
    $third = Category::factory()->create(['name' => 'News', 'slug' => null]);

    expect($second->slug)->toBe('news-2');
    expect($third->slug)->toBe('news-3');
});

it('keeps an explicitly provided slug', function () {
    expect(Post::factory()->create(['slug' => 'custom-slug'])->slug)->toBe('custom-slug');
});

it('does not collide a record with its own slug on update', function () {
    $tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => null]);
    $tag->update(['name' => 'Laravel Framework']);

    expect($tag->fresh()->slug)->toBe('laravel');
});
