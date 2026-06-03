<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('blog show renders a published post with SEO meta and JSON-LD', function () {
    $author = User::factory()->create(['name' => 'Jane Author', 'bio' => 'Writes things.']);
    $category = Category::factory()->create(['name' => 'Growth']);

    $post = Post::factory()->published()->for($author, 'author')->create([
        'title' => 'How to Convert Visitors',
        'slug' => 'how-to-convert-visitors',
        'meta_title' => 'Conversion Guide',
        'meta_description' => 'A guide to higher conversions.',
        'content' => '<p>'.str_repeat('Lorem ipsum dolor sit amet. ', 50).'</p>',
        'category_id' => $category->id,
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Show')
            ->where('post.title', 'How to Convert Visitors')
            ->where('post.author.name', 'Jane Author')
            ->where('post.author.bio', 'Writes things.')
            ->where('meta.title', 'Conversion Guide')
            ->where('meta.description', 'A guide to higher conversions.')
            ->where('meta.canonical', route('blog.show', 'how-to-convert-visitors'))
            ->where('schema.0.@type', 'BlogPosting')
            ->where('schema.1.@type', 'BreadcrumbList'));
});

test('blog show 404s for drafts and scheduled posts', function () {
    $author = User::factory()->create();

    $draft = Post::factory()->draft()->for($author, 'author')->create(['slug' => 'draft-slug']);
    $scheduled = Post::factory()->scheduled(now()->addDay())->for($author, 'author')->create(['slug' => 'scheduled-slug']);

    $this->get(route('blog.show', $draft->slug))->assertNotFound();
    $this->get(route('blog.show', $scheduled->slug))->assertNotFound();
});

test('blog show exposes the post for rendering share links', function () {
    $author = User::factory()->create();
    $post = Post::factory()->published()->for($author, 'author')->create(['slug' => 'shareable']);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Show')
            ->where('post.slug', 'shareable'));
});
