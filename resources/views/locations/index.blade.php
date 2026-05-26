@php
    $title = 'Service Areas — All American Web Design';
    $description = 'Web design and SEO for businesses across Florida\'s Gulf Coast — from Destin and 30A to Panama City Beach. Find your town.';

    $grouped = $locations->groupBy('region');

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Locations', 'item' => route('locations.index')],
        ],
    ];

    $itemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Service Areas',
        'itemListElement' => $locations->values()->map(fn ($location, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $location->display_name,
            'item' => route('location.show', $location->slug),
        ])->all(),
    ];
@endphp

<x-layouts.marketing :title="$title" :description="$description">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
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
                    <li class="text-white/85">Locations</li>
                </ol>
            </nav>
            <div class="max-w-3xl">
                <h1 class="font-serif text-6xl font-bold uppercase leading-[0.95] tracking-tight sm:text-7xl">
                    Gulf Coast
                    <span class="block text-[var(--color-red)]">service areas.</span>
                </h1>
                <p class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl">
                    We build websites and run SEO for businesses up and down Florida's Emerald Coast. Find your town.
                </p>
            </div>
        </div>

        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
    </section>

    {{-- ───────── Locations by region ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            @foreach ($grouped as $region => $regionLocations)
                <div class="@if (! $loop->first) mt-16 @endif">
                    @if ($region)
                        <h2 class="font-serif text-2xl font-bold uppercase tracking-tight text-[var(--color-deep-teal)]">{{ $region }}</h2>
                    @endif
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($regionLocations as $location)
                            <a href="{{ route('location.show', $location->slug) }}"
                               class="group rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-emerald-700)]/40 dark:bg-white/[0.04] dark:ring-white/10">
                                <h3 class="font-serif text-xl text-[var(--color-deep-teal)] group-hover:underline">{{ $location->name }}</h3>
                                @if ($location->hero_subhead)
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-white/70">{{ $location->hero_subhead }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts.marketing>
