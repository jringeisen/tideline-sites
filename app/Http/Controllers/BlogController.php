<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\MarketingSchema;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $q = trim((string) $request->query('q', ''));

        return $this->renderIndex(
            posts: $this->buildPostQuery($q),
            q: $q,
            activeCategory: null,
            activeTag: null,
        );
    }

    public function category(string $slug): Response
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return $this->renderIndex(
            posts: $posts,
            q: '',
            activeCategory: ['name' => $category->name, 'slug' => $category->slug],
            activeTag: null,
        );
    }

    public function tag(string $slug): Response
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->whereHas('tags', fn ($query) => $query->where('tags.id', $tag->id))
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return $this->renderIndex(
            posts: $posts,
            q: '',
            activeCategory: null,
            activeTag: ['name' => $tag->name, 'slug' => $tag->slug],
        );
    }

    public function show(string $slug): Response
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'tags', 'author'])
            ->firstOrFail();

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $title = $post->meta_title ?: $post->title;
        $description = $post->meta_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 160);
        $canonical = route('blog.show', $post->slug);
        $ogImage = $post->og_image_url ?: asset('og-image.png');

        $breadcrumbItems = array_values(array_filter([
            ['name' => 'Home', 'item' => url('/')],
            ['name' => 'Blog', 'item' => route('blog.index')],
            $post->category ? ['name' => $post->category->name, 'item' => route('blog.category', $post->category->slug)] : null,
            ['name' => $post->title, 'item' => $canonical],
        ]));

        return Inertia::render('Blog/Show', [
            'post' => [
                ...$this->toCard($post),
                'content' => $post->content,
                'author' => [
                    'name' => $post->author?->name,
                    'avatar_url' => $post->author?->avatar_url,
                    'bio' => $post->author?->bio,
                ],
            ],
            'related' => $related->map(fn (Post $related) => $this->toCard($related))->all(),
            'meta' => [
                'title' => $title,
                'description' => $description,
                'canonical' => $canonical,
                'ogImage' => $ogImage,
            ],
            'schema' => [
                MarketingSchema::blogPosting([
                    'title' => $post->title,
                    'datePublished' => $post->published_at?->toAtomString(),
                    'dateModified' => $post->updated_at?->toAtomString(),
                    'canonical' => $canonical,
                    'image' => $ogImage,
                    'authorName' => $post->author?->name,
                    'description' => $description,
                ]),
                MarketingSchema::breadcrumb($breadcrumbItems),
            ],
        ]);
    }

    /**
     * Render the blog index (also used for category/tag/search views).
     *
     * @param  array{name: string, slug: string}|null  $activeCategory
     * @param  array{name: string, slug: string}|null  $activeTag
     */
    private function renderIndex(LengthAwarePaginator $posts, string $q, ?array $activeCategory, ?array $activeTag): Response
    {
        $headline = match (true) {
            (bool) $activeCategory => "{$activeCategory['name']} — All American Web Design Blog",
            (bool) $activeTag => "#{$activeTag['name']} — All American Web Design Blog",
            $q !== '' => "Search: {$q} — All American Web Design Blog",
            default => 'Blog — All American Web Design',
        };

        $description = match (true) {
            (bool) $activeCategory => "Posts in the {$activeCategory['name']} category.",
            (bool) $activeTag => "Posts tagged #{$activeTag['name']}.",
            default => 'Marketing, SEO, and web-design notes from the All American Web Design team.',
        };

        return Inertia::render('Blog/Index', [
            'posts' => $posts->through(fn (Post $post) => $this->toCard($post)),
            'categories' => Category::query()->orderBy('name')->get(['name', 'slug']),
            'tags' => Tag::query()->orderBy('name')->get(['name', 'slug']),
            'q' => $q,
            'activeCategory' => $activeCategory,
            'activeTag' => $activeTag,
            'meta' => [
                'title' => $headline,
                'description' => $description,
                'canonical' => url()->current(),
            ],
        ]);
    }

    /**
     * Shape a post for the blog card / list item.
     *
     * @return array<string, mixed>
     */
    private function toCard(Post $post): array
    {
        return [
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'reading_time_minutes' => $post->reading_time_minutes,
            'published_at' => $post->published_at?->format('M j, Y'),
            'published_at_full' => $post->published_at?->format('F j, Y'),
            'published_at_iso' => $post->published_at?->toAtomString(),
            'category' => $post->category
                ? ['name' => $post->category->name, 'slug' => $post->category->slug]
                : null,
            'author' => [
                'name' => $post->author?->name,
                'avatar_url' => $post->author?->avatar_url,
            ],
            'tags' => $post->tags->map(fn (Tag $tag) => ['name' => $tag->name, 'slug' => $tag->slug])->all(),
        ];
    }

    private function buildPostQuery(string $q): LengthAwarePaginator
    {
        if ($q !== '') {
            return Post::search($q)
                ->query(fn ($query) => $query
                    ->with(['category', 'tags', 'author'])
                    ->orderByDesc('published_at'))
                ->paginate(10)
                ->withQueryString();
        }

        return Post::published()
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();
    }
}
