<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('category'), fn ($q, $categoryId) => $q->where('category_id', $categoryId))
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'status' => $request->query('status'),
                'category' => $request->query('category'),
                'q' => $request->query('q'),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Posts/Create', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name']),
            'statuses' => Post::STATUSES,
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;

        $post = Post::create(collect($data)->except('tags')->all());
        $post->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post saved.');
    }

    public function edit(Post $post): Response
    {
        $post->load('tags');

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post,
            'postTags' => $post->tags->pluck('id'),
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name']),
            'statuses' => Post::STATUSES,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        $post->fill(collect($data)->except('tags')->all());
        $post->save();
        $post->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post deleted.');
    }
}
