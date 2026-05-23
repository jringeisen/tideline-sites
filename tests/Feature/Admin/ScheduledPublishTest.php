<?php

use App\Models\Post;
use App\Models\User;

test('posts:publish-scheduled flips due scheduled posts to published', function () {
    $author = User::factory()->create();

    $due = Post::factory()->scheduled(now()->subMinute())->for($author, 'author')->create();
    $future = Post::factory()->scheduled(now()->addHour())->for($author, 'author')->create();
    $draft = Post::factory()->draft()->for($author, 'author')->create();

    $this->artisan('posts:publish-scheduled')->assertExitCode(0);

    expect($due->refresh()->status)->toBe(Post::STATUS_PUBLISHED)
        ->and($future->refresh()->status)->toBe(Post::STATUS_SCHEDULED)
        ->and($draft->refresh()->status)->toBe(Post::STATUS_DRAFT);
});
