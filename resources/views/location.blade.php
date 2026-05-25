@php
    $title = "{$location['name']} Web Design & SEO — All American Web Design";

    $businessId = url('/') . '#business';
    $pageUrl = url()->current();

    $localBusinessSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        '@id' => $pageUrl . '#business',
        'name' => 'All American Web Design — ' . $location['name'],
        'url' => $pageUrl,
        'description' => $location['meta_description'],
        'priceRange' => '$299 - $499/mo',
        'image' => asset('og-image.png'),
        'telephone' => '+1-850-684-8924',
        'email' => 'hello@allamericanwebdesign.com',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $location['name'],
            'addressRegion' => 'FL',
            'addressCountry' => 'US',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $location['geo']['lat'],
            'longitude' => $location['geo']['lng'],
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => $location['name'],
            'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => "Florida's Gulf Coast"],
        ],
        'parentOrganization' => ['@id' => $businessId],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Locations', 'item' => url('/locations')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $location['name'], 'item' => $pageUrl],
        ],
    ];
@endphp

<x-layouts.marketing
    :title="$title"
    :description="$location['meta_description']">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
        {{-- Base navy field --}}
        <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%);"></div>
        {{-- American flag, muted into the navy via luminosity blend --}}
        <img
            src="{{ asset('american-flag.png') }}"
            alt=""
            width="1729" height="910"
            class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
            loading="eager"
            fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"></div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <nav aria-label="Breadcrumb" class="mb-6 text-sm text-white/60">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li aria-hidden="true">›</li>
                    <li class="text-white/85">{{ $location['name'] }}</li>
                </ol>
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"></span>
                    Serving {{ $location['display_name'] }}
                </span>
                <h1 class="mt-6 font-serif text-6xl font-bold uppercase leading-[0.95] tracking-tight sm:text-7xl lg:text-8xl">
                    {{ $location['name'] }}
                    <span class="block text-[var(--color-red)]">{!! $location['tagline'] !!}.</span>
                </h1>
                <p class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl">
                    {!! $location['hero_subhead'] !!}
                </p>
                <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact.show') }}"
                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-black/30 transition hover:bg-[var(--color-red-deep)]">
                        Get a quote for your {{ $location['name'] }} business
                    </a>
                    <a href="{{ route('home') }}#pricing"
                       class="inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-white/10">
                        See pricing
                    </a>
                </div>
            </div>
        </div>

        {{-- Heritage stripe divider --}}
        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
    </section>

    {{-- ───────── Local context ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Why {{ $location['name'] }}</p>
            <h2 class="mt-3 font-serif text-3xl font-bold uppercase leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                Web design and SEO built for {{ $location['name'] }}.
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-slate-700">{{ $location['intro'] }}</p>
            <p class="mt-4 text-lg leading-relaxed text-slate-700">{{ $location['why_local'] }}</p>
        </div>
    </section>

    {{-- ───────── Segments ───────── --}}
    <section class="bg-[var(--color-sand-100)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Who we serve</p>
                <h2 class="mt-3 font-serif text-3xl font-bold uppercase tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                    {{ $location['name'] }} businesses we love working with.
                </h2>
            </div>

            <div class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-2">
                @foreach ($location['segments'] as [$name, $copy])
                    <article class="rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                        <h3 class="font-serif text-xl text-[var(--color-deep-teal)]">{!! $name !!}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{!! $copy !!}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ───────── Services overview ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">What we do</p>
                <h2 class="mt-3 font-serif text-3xl font-bold uppercase tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                    Everything {{ $location['name'] }} businesses need to win online.
                </h2>
            </div>

            <div class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Web Design', 'Fast, beautiful sites built for ' . $location['name'] . ' customers.'],
                    ['SEO Optimization', 'Local SEO that wins searches in ' . $location['name'] . ' and nearby towns.'],
                    ['Blog Writing', 'Original posts that pull search traffic into your business.'],
                    ['Newsletters', 'Monthly emails that keep past customers coming back.'],
                ] as [$name, $copy])
                    <article class="rounded-2xl border border-[var(--color-sand-300)]/60 bg-white p-6 dark:bg-white/[0.04] dark:border-white/10">
                        <h3 class="font-serif text-lg text-[var(--color-deep-teal)]">{{ $name }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>

            <p class="mt-10 text-center text-sm text-slate-600">
                <a href="{{ route('home') }}#services" class="font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline">See all four services →</a>
            </p>
        </div>
    </section>

    {{-- ───────── Nearby ───────── --}}
    @if (! empty($location['nearby']))
        <section class="bg-[var(--color-sand-100)] py-16">
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">We also serve</p>
                <h2 class="mt-3 font-serif text-2xl font-bold uppercase text-[var(--color-deep-teal)]">Nearby Gulf Coast towns</h2>
                <ul class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
                    @foreach ($location['nearby'] as [$nearbyName, $nearbySlug])
                        @if ($nearbySlug && config("locations.{$nearbySlug}"))
                            <li><a href="{{ route('location.show', $nearbySlug) }}"
                                   class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-emerald-50)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15 dark:hover:bg-white/15">{{ $nearbyName }}</a></li>
                        @else
                            <li class="rounded-full bg-white/60 px-3 py-1 text-slate-600 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">{{ $nearbyName }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ───────── Final CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] py-20 text-white sm:py-28">
        <div class="absolute inset-0 -z-10 opacity-50"
             style="background: radial-gradient(50% 60% at 50% 100%, rgba(59,83,120,0.5), transparent 60%);">
        </div>
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-3xl font-bold uppercase tracking-tight sm:text-4xl">
                Ready to put {{ $location['name'] }} on the map?
            </h2>
            <p class="mt-4 text-lg text-white/80">Tell us about your business and we'll be in touch within one business day.</p>
            <div class="mt-8">
                <a href="{{ route('contact.show') }}"
                   class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg transition hover:bg-[var(--color-sand-200)]">
                    Get in touch
                </a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
