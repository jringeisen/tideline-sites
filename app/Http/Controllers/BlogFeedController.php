<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class BlogFeedController extends Controller
{
    public function __invoke(): Response
    {
        $posts = Post::published()
            ->with(['author', 'category'])
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        return response()
            ->view('blog.rss', ['posts' => $posts])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
