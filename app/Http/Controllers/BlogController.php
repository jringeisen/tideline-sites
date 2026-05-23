<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $posts = $this->buildPostQuery($q);

        return view('blog.index', [
            'posts' => $posts,
            'categories' => Category::query()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'q' => $q,
            'activeCategory' => null,
            'activeTag' => null,
        ]);
    }

    public function category(string $slug): View
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => Category::query()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'q' => '',
            'activeCategory' => $category,
            'activeTag' => null,
        ]);
    }

    public function tag(string $slug): View
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->whereHas('tags', fn ($query) => $query->where('tags.id', $tag->id))
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => Category::query()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'q' => '',
            'activeCategory' => null,
            'activeTag' => $tag,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'tags', 'author'])
            ->firstOrFail();

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'related' => $related,
        ]);
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
