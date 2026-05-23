<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

test('sitemap includes published posts, categories, and tags but not drafts', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create(['slug' => 'growth']);
    $tag = Tag::factory()->create(['slug' => 'seo']);

    $published = Post::factory()->published()->for($author, 'author')->create([
        'slug' => 'sitemap-published',
        'category_id' => $category->id,
    ]);
    $published->tags()->attach($tag->id);

    Post::factory()->draft()->for($author, 'author')->create(['slug' => 'sitemap-draft']);

    $response = $this->get('/sitemap.xml');

    $response->assertOk()
        ->assertSee('<loc>'.route('blog.index').'</loc>', false)
        ->assertSee('<loc>'.route('blog.show', 'sitemap-published').'</loc>', false)
        ->assertSee('<loc>'.route('blog.category', 'growth').'</loc>', false)
        ->assertSee('<loc>'.route('blog.tag', 'seo').'</loc>', false)
        ->assertDontSee('sitemap-draft');
});

test('sitemap includes a lastmod for published posts', function () {
    $author = User::factory()->create();
    $publishedAt = now()->subDay();
    Post::factory()->published($publishedAt)->for($author, 'author')->create(['slug' => 'dated-post']);

    $this->get('/sitemap.xml')
        ->assertSee($publishedAt->toAtomString(), false);
});
