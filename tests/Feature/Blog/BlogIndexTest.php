<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

test('blog index renders and lists published posts', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Marketing']);

    Post::factory()->published()->for($author, 'author')->create([
        'title' => 'Published Headline',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('blog.index'));

    $response->assertOk()
        ->assertSee('Published Headline')
        ->assertSee('Marketing');
});

test('blog index hides draft and scheduled posts', function () {
    $author = User::factory()->create();

    Post::factory()->draft()->for($author, 'author')->create(['title' => 'Hidden Draft']);
    Post::factory()->scheduled(now()->addDay())->for($author, 'author')->create(['title' => 'Hidden Scheduled']);
    Post::factory()->published()->for($author, 'author')->create(['title' => 'Visible Live']);

    $response = $this->get(route('blog.index'));

    $response->assertOk()
        ->assertSee('Visible Live')
        ->assertDontSee('Hidden Draft')
        ->assertDontSee('Hidden Scheduled');
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

    $response = $this->get(route('blog.category', 'marketing'));

    $response->assertOk()
        ->assertSee('Marketing Post')
        ->assertDontSee('Design Post');
});

test('blog index filters by tag slug', function () {
    $author = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'SEO', 'slug' => 'seo']);

    $tagged = Post::factory()->published()->for($author, 'author')->create(['title' => 'Tagged Post']);
    $tagged->tags()->attach($tag->id);

    Post::factory()->published()->for($author, 'author')->create(['title' => 'Untagged Post']);

    $response = $this->get(route('blog.tag', 'seo'));

    $response->assertOk()
        ->assertSee('Tagged Post')
        ->assertDontSee('Untagged Post');
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

    $response = $this->get(route('blog.index', ['q' => 'wave']));

    $response->assertOk()
        ->assertSee('Wave Surfing in Destin')
        ->assertDontSee('Mountain Biking');
});
