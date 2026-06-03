<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/**
 * @return array<int, string>
 */
function postTitles(mixed $data): array
{
    return collect($data)->pluck('title')->all();
}

test('blog index renders and lists published posts', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Marketing']);

    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Published Headline',
        'category_id' => $category->id,
    ]);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Index')
            ->where('posts.data', fn ($data) => in_array('Published Headline', postTitles($data), true))
            ->where('posts.data.0.category.name', 'Marketing'));
});

test('blog index hides draft and scheduled posts', function () {
    $author = User::factory()->create();

    Post::factory()->draft()->for($author, 'author')->create(['title' => 'Hidden Draft']);
    Post::factory()->scheduled(now()->addDay())->for($author, 'author')->create(['title' => 'Hidden Scheduled']);
    Post::factory()->published()->for($author, 'author')->create(['title' => 'Visible Live']);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('posts.data', fn ($data) => postTitles($data) === ['Visible Live']));
});

test('blog index filters by category slug', function () {
    $author = User::factory()->create();
    $marketing = Category::factory()->create(['name' => 'Marketing', 'slug' => 'marketing']);
    $design = Category::factory()->create(['name' => 'Design', 'slug' => 'design']);

    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Marketing Post',
        'category_id' => $marketing->id,
    ]);
    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Design Post',
        'category_id' => $design->id,
    ]);

    $this->get(route('blog.category', 'marketing'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('activeCategory.slug', 'marketing')
            ->where('posts.data', fn ($data) => postTitles($data) === ['Marketing Post']));
});

test('blog index filters by tag slug', function () {
    $author = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'SEO', 'slug' => 'seo']);

    $tagged = Post::factory()->published()->for($author, 'author')->create(['title' => 'Tagged Post']);
    $tagged->tags()->attach($tag->id);

    Post::factory()->published()->for($author, 'author')->create(['title' => 'Untagged Post']);

    $this->get(route('blog.tag', 'seo'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('activeTag.slug', 'seo')
            ->where('posts.data', fn ($data) => postTitles($data) === ['Tagged Post']));
});

test('blog index supports search via Scout database driver', function () {
    config()->set('scout.driver', 'database');

    $author = User::factory()->create();

    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Wave Surfing in Destin',
    ]);
    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Mountain Biking',
    ]);

    $this->get(route('blog.index', ['q' => 'wave']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('q', 'wave')
            ->where('posts.data', fn ($data) => postTitles($data) === ['Wave Surfing in Destin']));
});
