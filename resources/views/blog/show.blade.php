@php
    /** @var \App\Models\Post $post */
    /** @var \Illuminate\Database\Eloquent\Collection $related */

    $title = $post->meta_title ?: $post->title;
    $description = $post->meta_description ?: $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160);
    $canonical = route('blog.show', $post->slug);
    $ogImage = $post->og_image_url ?: asset('og-image.jpg');

    $blogPostingSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified' => $post->updated_at?->toAtomString(),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
        'image' => $ogImage,
        'author' => [
            '@type' => 'Person',
            'name' => $post->author?->name,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'All American Web Design',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('og-image.jpg')],
        ],
        'description' => $description,
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            $post->category ? ['@type' => 'ListItem', 'position' => 3, 'name' => $post->category->name, 'item' => route('blog.category', $post->category->slug)] : null,
            ['@type' => 'ListItem', 'position' => $post->category ? 4 : 3, 'name' => $post->title, 'item' => $canonical],
        ])),
    ];

    $shareText = rawurlencode($post->title);
    $shareUrl = rawurlencode($canonical);
@endphp

<x-layouts.marketing :title="$title" :description="$description" :canonical="$canonical" :ogImage="$ogImage">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($blogPostingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    <article class="bg-[var(--color-cream)]">
        <header class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
            <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%);"></div>
            <img src="{{ asset('american-flag.png') }}" alt="" width="1729" height="910"
                 class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity" loading="eager" fetchpriority="high">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"></div>
            <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"></div>
            <div class="mx-auto max-w-3xl px-6 pt-32 pb-20 sm:pt-40 lg:px-8">
                <nav aria-label="Breadcrumb" class="text-sm text-white/60">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                        <li aria-hidden="true">›</li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a></li>
                        @if ($post->category)
                            <li aria-hidden="true">›</li>
                            <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-white">{{ $post->category->name }}</a></li>
                        @endif
                    </ol>
                </nav>
                <h1 class="mt-6 font-serif text-4xl font-bold leading-tight tracking-tight sm:text-5xl">{{ $post->title }}</h1>
                <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-white/75">
                    <span>{{ $post->author?->name ?? 'All American Web Design' }}</span>
                    <time datetime="{{ $post->published_at?->toAtomString() }}">{{ $post->published_at?->format('F j, Y') }}</time>
                    <span>{{ $post->reading_time_minutes }} min read</span>
                </div>
            </div>

            {{-- Heritage stripe divider --}}
            <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
        </header>

        <div class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
            <div class="prose prose-slate max-w-none prose-headings:font-serif prose-headings:text-[var(--color-deep-teal)] prose-a:text-[var(--color-emerald-700)] prose-img:rounded-xl dark:prose-invert">
                {!! $post->content !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <ul class="mt-12 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <li>
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                               class="inline-flex rounded-full bg-[var(--color-sand-100)] px-3 py-1 text-sm text-[var(--color-deep-teal)] hover:bg-[var(--color-sand-200)] dark:bg-white/[0.06] dark:text-white">
                                #{{ $tag->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <section aria-label="Share this post" class="mt-12 flex flex-wrap items-center gap-3 border-y border-[var(--color-sand-300)] py-6">
                <span class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Share</span>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)]">Twitter</a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)]">LinkedIn</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)]">Facebook</a>
                <a href="mailto:?subject={{ $shareText }}&body={{ $shareUrl }}"
                   class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)]">Email</a>
            </section>

            @if ($post->author?->bio)
                <aside class="mt-12 flex items-start gap-5 rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                    @if ($post->author->avatar_url)
                        <img src="{{ $post->author->avatar_url }}" alt="" class="h-14 w-14 rounded-full object-cover">
                    @endif
                    <div>
                        <p class="font-serif text-lg text-[var(--color-deep-teal)]">{{ $post->author->name }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $post->author->bio }}</p>
                    </div>
                </aside>
            @endif
        </div>

        @if ($related->isNotEmpty())
            <section class="bg-[var(--color-sand-100)] py-16">
                <div class="mx-auto max-w-5xl px-6 lg:px-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Keep reading</p>
                    <h2 class="mt-3 font-serif text-3xl font-bold uppercase tracking-tight text-[var(--color-deep-teal)]">More from the blog</h2>
                    <div class="mt-8 grid gap-6 sm:grid-cols-3">
                        @foreach ($related as $post)
                            @include('blog.partials._card', ['post' => $post])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>

</x-layouts.marketing>
