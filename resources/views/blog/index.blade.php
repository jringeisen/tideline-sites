@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $posts */
    /** @var \Illuminate\Database\Eloquent\Collection $categories */
    /** @var \Illuminate\Database\Eloquent\Collection $tags */
    /** @var \App\Models\Category|null $activeCategory */
    /** @var \App\Models\Tag|null $activeTag */

    $headline = match (true) {
        (bool) $activeCategory => "{$activeCategory->name} — Tideline Blog",
        (bool) $activeTag => "#{$activeTag->name} — Tideline Blog",
        $q !== '' => "Search: {$q} — Tideline Blog",
        default => 'Blog — Tideline Sites',
    };

    $description = match (true) {
        (bool) $activeCategory => "Posts in the {$activeCategory->name} category.",
        (bool) $activeTag => "Posts tagged #{$activeTag->name}.",
        default => 'Marketing, SEO, and web-design notes from the Tideline Sites team.',
    };
@endphp

<x-layouts.marketing :title="$headline" :description="$description">

    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="absolute inset-0 -z-10"
             style="background:
                radial-gradient(50% 70% at 80% 0%, rgba(16,185,129,0.30), transparent 60%),
                radial-gradient(50% 60% at 0% 100%, rgba(15,118,110,0.30), transparent 60%),
                linear-gradient(180deg, #0b2a2e 0%, #0d4742 100%);">
        </div>
        <div class="mx-auto max-w-5xl px-6 pt-32 pb-16 sm:pt-40 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-sand-200)]">Tideline blog</p>
            <h1 class="mt-4 font-serif text-5xl leading-[1.05] tracking-tight sm:text-6xl">
                @if ($activeCategory)
                    {{ $activeCategory->name }}
                @elseif ($activeTag)
                    #{{ $activeTag->name }}
                @else
                    Notes from the <span class="italic text-[var(--color-sand-200)]">Emerald Coast</span>.
                @endif
            </h1>
            <p class="mt-6 max-w-2xl text-lg text-white/85">
                Practical thinking on web design, SEO, and growing a local business online.
            </p>
        </div>
        <svg class="block w-full text-[var(--color-cream)]" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 40 C 240 80 480 0 720 40 S 1200 80 1440 40 L 1440 80 L 0 80 Z" />
        </svg>
    </section>

    <section class="bg-[var(--color-cream)] py-12">
        <div class="mx-auto max-w-5xl px-6 lg:px-8">
            <form action="{{ route('blog.index') }}" method="get" class="flex items-center gap-3">
                <label for="q" class="sr-only">Search the blog</label>
                <input id="q"
                       name="q"
                       type="search"
                       value="{{ $q }}"
                       placeholder="Search posts…"
                       class="w-full rounded-full border border-[var(--color-sand-300)] bg-white px-5 py-3 text-sm text-[var(--color-deep-teal)] placeholder:text-slate-400 focus:border-[var(--color-emerald-700)] focus:outline-none focus:ring-2 focus:ring-[var(--color-emerald-700)]/30 dark:bg-white/[0.04] dark:text-white">
                <button type="submit"
                        class="inline-flex shrink-0 rounded-full bg-[var(--color-emerald-900)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-deep-teal)]">
                    Search
                </button>
            </form>

            @if ($categories->isNotEmpty())
                <div class="mt-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Categories</p>
                    <ul class="mt-3 flex flex-wrap gap-2">
                        <li>
                            <a href="{{ route('blog.index') }}"
                               class="inline-flex rounded-full px-3 py-1 text-sm ring-1 transition {{ ! $activeCategory && ! $activeTag ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)]' : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)]' }}">
                                All
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}"
                                   class="inline-flex rounded-full px-3 py-1 text-sm ring-1 transition {{ $activeCategory?->id === $category->id ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)]' : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)]' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-[var(--color-cream)] pb-20">
        <div class="mx-auto max-w-5xl px-6 lg:px-8">
            @if ($posts->isEmpty())
                <div class="rounded-2xl border border-dashed border-[var(--color-sand-300)] bg-white p-12 text-center">
                    <p class="font-serif text-2xl text-[var(--color-deep-teal)]">No posts found.</p>
                    @if ($q !== '')
                        <p class="mt-2 text-sm text-slate-600">Nothing matched "{{ $q }}". Try a broader query.</p>
                    @endif
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ($posts as $post)
                        @include('blog.partials._card', ['post' => $post])
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.marketing>
