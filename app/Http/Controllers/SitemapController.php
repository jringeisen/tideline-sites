<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use App\Models\Post;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('service-area'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('contact.show'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('blog.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
        ];

        $urls[] = ['loc' => route('services.index'), 'priority' => '0.8', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => route('locations.index'), 'priority' => '0.7', 'changefreq' => 'monthly'];

        foreach (Service::published()->orderBy('sort_order')->get() as $service) {
            $urls[] = [
                'loc' => route('services.show', $service->slug),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $service->updated_at?->toAtomString(),
            ];
        }

        foreach (Location::published()->orderBy('sort_order')->get() as $location) {
            $urls[] = [
                'loc' => route('location.show', $location->slug),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $location->updated_at?->toAtomString(),
            ];
        }

        foreach (Post::published()->orderByDesc('published_at')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $post->published_at?->toAtomString(),
            ];
        }

        foreach (Category::query()->orderBy('slug')->get() as $category) {
            $urls[] = [
                'loc' => route('blog.category', $category->slug),
                'priority' => '0.5',
                'changefreq' => 'monthly',
            ];
        }

        foreach (Tag::query()->orderBy('slug')->get() as $tag) {
            $urls[] = [
                'loc' => route('blog.tag', $tag->slug),
                'priority' => '0.4',
                'changefreq' => 'monthly',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
