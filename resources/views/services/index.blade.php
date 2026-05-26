@php
    $title = 'Web Design & SEO Services — All American Web Design';
    $description = 'Web design, SEO optimization, blog writing, and newsletters for American small businesses. Explore everything we offer.';

    $businessId = url('/') . '#business';

    $servicesSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'All American Web Design Services',
        'itemListElement' => $services->values()->map(fn ($service, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'item' => [
                '@type' => 'Service',
                'name' => $service->name,
                'description' => $service->summary,
                'url' => route('services.show', $service->slug),
                'provider' => ['@id' => $businessId],
            ],
        ])->all(),
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => route('services.index')],
        ],
    ];
@endphp

<x-layouts.marketing :title="$title" :description="$description">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($servicesSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
        <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%);"></div>
        <img src="{{ asset('american-flag.png') }}" alt="" width="1729" height="910"
             class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity" loading="eager" fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"></div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <nav aria-label="Breadcrumb" class="mb-6 text-sm text-white/60">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li aria-hidden="true">›</li>
                    <li class="text-white/85">Services</li>
                </ol>
            </nav>
            <div class="max-w-3xl">
                <h1 class="font-serif text-6xl font-bold uppercase leading-[0.95] tracking-tight sm:text-7xl">
                    What we
                    <span class="block text-[var(--color-red)]">do.</span>
                </h1>
                <p class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl">
                    Everything an American small business needs to win online — designed, written, and built by us.
                </p>
                <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact.show') }}"
                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-black/30 transition hover:bg-[var(--color-red-deep)]">
                        Get a quote
                    </a>
                    <a href="{{ route('home') }}#pricing"
                       class="inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-white/10">
                        See pricing
                    </a>
                </div>
            </div>
        </div>

        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
    </section>

    {{-- ───────── Services grid ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}"
                       class="group flex flex-col rounded-3xl bg-white p-8 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-emerald-700)]/40 dark:bg-white/[0.04] dark:ring-white/10">
                        @if ($service->icon)
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-emerald-50)] text-[var(--color-emerald-700)] dark:bg-white/[0.06]">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6" aria-hidden="true"><path d="{{ $service->icon }}" /></svg>
                            </span>
                        @endif
                        <h2 class="mt-5 font-serif text-2xl text-[var(--color-deep-teal)]">{{ $service->name }}</h2>
                        <p class="mt-2 flex-1 leading-relaxed text-slate-600 dark:text-white/70">{{ $service->summary }}</p>
                        <span class="mt-5 text-sm font-semibold text-[var(--color-emerald-700)] underline-offset-4 group-hover:underline">
                            Learn more →
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ───────── Final CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] py-20 text-white sm:py-28">
        <div class="absolute inset-0 -z-10 opacity-50"
             style="background: radial-gradient(50% 60% at 50% 100%, rgba(59,83,120,0.5), transparent 60%);"></div>
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-3xl font-bold uppercase tracking-tight sm:text-4xl">Not sure where to start?</h2>
            <p class="mt-4 text-lg text-white/80">Tell us about your business and we'll recommend the right plan within one business day.</p>
            <div class="mt-8">
                <a href="{{ route('contact.show') }}"
                   class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg transition hover:bg-[var(--color-sand-200)]">
                    Get in touch
                </a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
