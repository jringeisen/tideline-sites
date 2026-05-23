<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

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

    $response = $this->get(route('blog.show', $post->slug));

    $response->assertOk()
        ->assertSee('How to Convert Visitors')
        ->assertSee('<title>Conversion Guide', false)
        ->assertSee('A guide to higher conversions.')
        ->assertSee('<link rel="canonical"', false)
        ->assertSee('"@type":"BlogPosting"', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('Jane Author')
        ->assertSee('Writes things.')
        ->assertSee('min read');
});

test('blog show 404s for drafts and scheduled posts', function () {
    $author = User::factory()->create();

    $draft = Post::factory()->draft()->for($author, 'author')->create(['slug' => 'draft-slug']);
    $scheduled = Post::factory()->scheduled(now()->addDay())->for($author, 'author')->create(['slug' => 'scheduled-slug']);

    $this->get(route('blog.show', $draft->slug))->assertNotFound();
    $this->get(route('blog.show', $scheduled->slug))->assertNotFound();
});

test('blog show renders share links', function () {
    $author = User::factory()->create();
    $post = Post::factory()->published()->for($author, 'author')->create(['slug' => 'shareable']);

    $this->get(route('blog.show', $post->slug))
        ->assertSee('twitter.com/intent/tweet', false)
        ->assertSee('linkedin.com/sharing', false)
        ->assertSee('facebook.com/sharer', false);
});
