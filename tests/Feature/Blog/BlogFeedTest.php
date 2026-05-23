<?php

use App\Models\Post;
use App\Models\User;

test('rss feed renders the 20 most recent published posts', function () {
    $author = User::factory()->create();

    Post::factory()->count(25)->published()->for($author, 'author')->create();
    Post::factory()->draft()->for($author, 'author')->create(['title' => 'Draft Out']);

    $response = $this->get(route('blog.rss'));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
        ->assertSee('<rss', false)
        ->assertDontSee('Draft Out');

    expect(substr_count($response->getContent(), '<item>'))->toBe(20);
});

test('rss feed is valid XML', function () {
    $author = User::factory()->create();
    Post::factory()->count(3)->published()->for($author, 'author')->create();

    $body = $this->get(route('blog.rss'))->getContent();

    $previousErrors = libxml_use_internal_errors(true);
    $doc = simplexml_load_string($body);
    libxml_use_internal_errors($previousErrors);

    expect($doc)->not->toBeFalse();
});
