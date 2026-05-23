@php
    $cities = [
        'Destin', 'Miramar Beach', 'Sandestin', 'Santa Rosa Beach',
        'Blue Mountain Beach', 'Grayton Beach', 'WaterColor', 'Seaside',
        'Seagrove Beach', 'WaterSound', 'Seacrest Beach', 'Alys Beach',
        'Rosemary Beach', 'Inlet Beach', 'Panama City Beach', 'Panama City',
    ];

    $cityLinks = [
        'Destin' => 'destin',
        'Santa Rosa Beach' => '30a',
        'Grayton Beach' => '30a',
        'WaterColor' => '30a',
        'Seaside' => '30a',
        'Seagrove Beach' => '30a',
        'WaterSound' => '30a',
        'Seacrest Beach' => '30a',
        'Alys Beach' => '30a',
        'Rosemary Beach' => '30a',
        'Inlet Beach' => '30a',
        'Panama City Beach' => 'panama-city-beach',
    ];

    $featuredLocations = [
        [
            'slug' => 'destin',
            'name' => 'Destin',
            'blurb' => 'From the Destin Harbor to Crab Island — websites for charter captains, beachfront restaurants, vacation rentals, and year-round local services.',
        ],
        [
            'slug' => '30a',
            'name' => '30A',
            'blurb' => 'Seaside, WaterColor, Alys, Rosemary, Inlet, and the rest of the 30A corridor — sites built for the boutique communities that define the brand.',
        ],
        [
            'slug' => 'panama-city-beach',
            'name' => 'Panama City Beach',
            'blurb' => 'From Pier Park to Front Beach Road — websites and SEO that help PCB businesses win year-round, not just during spring break.',
        ],
    ];

    $businessId = url('/') . '#business';

    $serviceAreaSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Web Design & SEO on the Emerald Coast',
        'provider' => ['@id' => $businessId],
        'areaServed' => array_map(fn ($city) => [
            '@type' => 'City',
            'name' => $city,
            'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Emerald Coast, Florida'],
        ], $cities),
    ];
@endphp

<x-layouts.marketing
    title="Service Area — Tideline Sites | Emerald Coast Web Design &amp; SEO"
    description="Tideline Sites serves businesses from Destin to Panama City Beach — including 30A, Sandestin, Seaside, Rosemary Beach, and the surrounding Emerald Coast communities.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($serviceAreaSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="mx-auto max-w-7xl px-6 pt-32 pb-20 sm:pt-40 sm:pb-28 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-white/70">Service area</p>
            <h1 class="mt-4 max-w-3xl font-serif text-4xl leading-tight tracking-tight sm:text-6xl">
                Emerald Coast web design &amp; SEO, from Destin to Panama City Beach.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/80">
                We're locals. We know that what works for a Seaside boutique isn't what works for a Destin charter captain — and we build every site to win in the towns it serves.
            </p>
            <dl class="mt-10 grid max-w-md grid-cols-2 gap-6">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-white/60">Towns served</dt>
                    <dd class="mt-1 font-serif text-4xl">{{ count($cities) }}+</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-white/60">Miles of coast</dt>
                    <dd class="mt-1 font-serif text-4xl">~50</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- ───────── Featured locations ───────── --}}
    <section class="bg-[var(--color-cream)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="font-serif text-3xl tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                Where we work
            </h2>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-slate-600">
                Three primary markets along the Emerald Coast. Each has its own dedicated page with industry-specific notes — and we serve every community in between.
            </p>

            <div class="mt-12 grid gap-6 lg:grid-cols-3">
                @foreach ($featuredLocations as $location)
                    <a href="{{ route('location.show', $location['slug']) }}"
                       class="group relative flex flex-col rounded-3xl bg-white p-8 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 transition hover:shadow-md hover:ring-[var(--color-emerald-600)]/40">
                        <h3 class="font-serif text-2xl text-[var(--color-deep-teal)]">{{ $location['name'] }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $location['blurb'] }}</p>
                        <span class="mt-6 inline-flex items-center text-sm font-semibold text-[var(--color-emerald-700)]">
                            See {{ $location['name'] }} details
                            <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ───────── Full city list ───────── --}}
    <section class="relative overflow-hidden bg-[var(--color-sand-100)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-start gap-16 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <h2 class="font-serif text-3xl tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                        Every town along the coast
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-600">
                        From Destin through 30A and out to Panama City Beach, we work with local service businesses and tourism operators in every community along the Emerald Coast.
                    </p>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Don't see your town? We probably serve it too — drop us a line and we'll let you know.
                    </p>
                    <a href="{{ route('contact.show') }}"
                       class="mt-8 inline-flex items-center rounded-full bg-[var(--color-emerald-700)] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[var(--color-emerald-800)]">
                        Ask about your area
                    </a>
                </div>

                <div class="lg:col-span-7">
                    <div class="relative rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8">
                        <x-marketing.coastline class="mb-6 h-24 w-full text-[var(--color-emerald-600)]" />

                        <ul class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm sm:grid-cols-3">
                            @foreach ($cities as $city)
                                <li class="flex items-center gap-2 text-slate-700">
                                    <svg class="h-4 w-4 flex-none text-[var(--color-emerald-600)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                    @if (isset($cityLinks[$city]))
                                        <a href="{{ route('location.show', $cityLinks[$city]) }}" class="hover:text-[var(--color-emerald-700)] hover:underline underline-offset-2">{{ $city }}</a>
                                    @else
                                        {{ $city }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Final CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] py-20 text-white sm:py-24">
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-3xl tracking-tight sm:text-4xl">Ready to win in your town?</h2>
            <p class="mt-4 text-lg leading-relaxed text-white/80">
                Tell us where you are and what you do. We'll show you exactly how a Tideline site can move the needle.
            </p>
            <a href="{{ route('contact.show') }}"
               class="mt-8 inline-flex items-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-sm transition hover:bg-[var(--color-sand-200)]">
                Start the conversation
            </a>
        </div>
    </section>
</x-layouts.marketing>
