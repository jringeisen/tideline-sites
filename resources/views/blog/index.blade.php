@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $posts */
    /** @var \Illuminate\Database\Eloquent\Collection $categories */
    /** @var \Illuminate\Database\Eloquent\Collection $tags */
    /** @var \App\Models\Category|null $activeCategory */
    /** @var \App\Models\Tag|null $activeTag */

    $headline = match (true) {
        (bool) $activeCategory => "{$activeCategory->name} — All American Web Design Blog",
        (bool) $activeTag => "#{$activeTag->name} — All American Web Design Blog",
        $q !== '' => "Search: {$q} — All American Web Design Blog",
        default => 'Blog — All American Web Design',
    };

    $description = match (true) {
        (bool) $activeCategory => "Posts in the {$activeCategory->name} category.",
        (bool) $activeTag => "Posts tagged #{$activeTag->name}.",
        default => 'Marketing, SEO, and web-design notes from the All American Web Design team.',
    };
@endphp

<x-layouts.marketing :title="$headline" :description="$description">

    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
        <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%);"></div>
        <img src="{{ asset('american-flag.png') }}" alt="" width="1729" height="910"
             class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity" loading="eager" fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"></div>
        <div class="mx-auto max-w-5xl px-6 pt-32 pb-16 sm:pt-40 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">All American Web Design blog</p>
            <h1 class="mt-4 font-serif text-5xl font-bold uppercase leading-[0.95] tracking-tight sm:text-6xl">
                @if ($activeCategory)
                    {{ $activeCategory->name }}
                @elseif ($activeTag)
                    #{{ $activeTag->name }}
                @else
                    Notes from the <span class="text-[var(--color-red)]">workshop</span>.
                @endif
            </h1>
            <p class="mt-6 max-w-2xl text-lg text-white/85">
                Practical thinking on web design, SEO, and growing a small business online.
            </p>
        </div>
        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
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
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Categories</p>
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
