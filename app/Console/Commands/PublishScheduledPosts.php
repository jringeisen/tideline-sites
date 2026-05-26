<?php

namespace App\Console\Commands;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('posts:publish-scheduled')]
#[Description('Publish scheduled posts whose published_at has arrived.')]
class PublishScheduledPosts extends Command
{
    public function handle(): int
    {
        $count = Post::query()
            ->where('status', PostStatus::Scheduled->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => PostStatus::Published->value]);

        $this->info("Published {$count} scheduled post(s).");

        return self::SUCCESS;
    }
}
