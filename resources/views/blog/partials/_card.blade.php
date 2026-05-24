@php
    /** @var \App\Models\Post $post */
@endphp
<article class="group flex flex-col rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-emerald-700)]/30 dark:bg-white/[0.04] dark:ring-white/10">
    @if ($post->category)
        <a href="{{ route('blog.category', $post->category->slug) }}"
           class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)] hover:underline">
            {{ $post->category->name }}
        </a>
    @endif

    <h3 class="mt-3 font-serif text-2xl leading-tight tracking-tight text-[var(--color-deep-teal)]">
        <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
    </h3>

    @if ($post->excerpt)
        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-600">{{ $post->excerpt }}</p>
    @endif

    <div class="mt-6 flex items-center justify-between text-xs text-slate-500">
        <div class="flex items-center gap-2">
            @if ($post->author?->avatar_url)
                <img src="{{ $post->author->avatar_url }}" alt="" class="h-6 w-6 rounded-full object-cover">
            @endif
            <span>{{ $post->author?->name ?? 'All American Web Design' }}</span>
        </div>
        <div class="flex items-center gap-3">
            <time datetime="{{ $post->published_at?->toAtomString() }}">
                {{ $post->published_at?->format('M j, Y') }}
            </time>
            <span>· {{ $post->reading_time_minutes }} min read</span>
        </div>
    </div>

    @if ($post->tags->isNotEmpty())
        <ul class="mt-4 flex flex-wrap gap-1.5">
            @foreach ($post->tags as $tag)
                <li>
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="inline-flex rounded-full bg-[var(--color-sand-100)] px-2.5 py-0.5 text-xs text-[var(--color-deep-teal)] hover:bg-[var(--color-sand-200)] dark:bg-white/[0.06] dark:text-white">
                        #{{ $tag->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</article>
