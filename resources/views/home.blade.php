@php
    $cities = [
        'Destin', 'Miramar Beach', 'Sandestin', 'Santa Rosa Beach',
        'Blue Mountain Beach', 'Grayton Beach', 'WaterColor', 'Seaside',
        'Seagrove Beach', 'WaterSound', 'Seacrest Beach', 'Alys Beach',
        'Rosemary Beach', 'Inlet Beach', 'Panama City Beach', 'Panama City',
    ];

    $services = [
        [
            'name' => 'Web Design',
            'description' => 'Hand-crafted, lightning-fast websites built to convert visitors into customers.',
            'icon' => 'M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5v8a1.5 1.5 0 01-1.5 1.5h-4l.4 2H13a.75.75 0 010 1.5H7a.75.75 0 010-1.5h1.1l.4-2h-4A1.5 1.5 0 013 12.5v-8z',
        ],
        [
            'name' => 'SEO Optimization',
            'description' => 'On-page SEO, local listings, and ongoing optimization so the right people find you on Google.',
            'icon' => 'M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.45 4.39l3.08 3.08a.75.75 0 11-1.06 1.06l-3.08-3.08A7 7 0 012 9z',
        ],
        [
            'name' => 'Blog Writing',
            'description' => 'Original articles that rank, educate your customers, and turn your site into a magnet for search traffic.',
            'icon' => 'M3.5 3A1.5 1.5 0 002 4.5v11A1.5 1.5 0 003.5 17h13a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0016.5 3h-13zM5 7.25A.75.75 0 015.75 6.5h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 7.25zM5.75 10a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zM5 13.75a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z',
        ],
        [
            'name' => 'Newsletters',
            'description' => 'Beautifully designed monthly newsletters that keep your business top-of-mind with past customers.',
            'icon' => 'M2.5 4A1.5 1.5 0 001 5.5v9A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0017.5 4h-15zM3 6.18l6.4 4.27a1.5 1.5 0 001.66 0L17.5 6.18V14.5H3V6.18zM16.1 5.5L10 9.57 3.9 5.5h12.2z',
        ],
    ];

    $faqs = [
        ['question' => 'Do I own my website?', 'answer' => 'Yes — always. The design, content, and domain are yours. If you ever leave us, we hand it off cleanly.'],
        ['question' => 'Is there a contract?', 'answer' => 'No long-term contracts. Essential and Growth are month-to-month — cancel anytime. Build & Own is a one-time build with $20/month hosting you can cancel anytime.'],
        ['question' => 'How fast can my site launch?', 'answer' => 'Most Essential sites launch in one to two weeks from kickoff. Growth plan sites with extra content take two to three weeks.'],
        ['question' => 'Do you work with my industry?', 'answer' => 'We specialize in local service businesses (HVAC, contractors, med spas, lawyers, dentists) and tourism & hospitality (vacation rentals, charters, restaurants, boutiques) along the Emerald Coast.'],
        ['question' => 'What if I already have a website?', 'answer' => 'We can redesign it, optimize what you have, or run SEO and content on your existing site — whichever makes sense.'],
        ['question' => 'Are there setup fees?', 'answer' => 'No setup fees on Essential or Growth — your first month covers design and launch. Build & Own is a one-time project starting at $1,000; contact us for a quote.'],
    ];

    $businessId = url('/') . '#business';

    $localBusinessSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        '@id' => $businessId,
        'name' => 'Tideline Sites',
        'url' => url('/'),
        'description' => 'Web design, SEO optimization, blog writing, and newsletters for businesses on the Emerald Coast.',
        'priceRange' => '$299 - $499/mo',
        'image' => asset('og-image.jpg'),
        'telephone' => '+1-850-684-8924',
        'email' => 'hello@tidelinesites.com',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Panama City Beach',
            'addressRegion' => 'FL',
            'addressCountry' => 'US',
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '09:00',
            'closes' => '17:00',
        ],
        'areaServed' => array_map(fn ($city) => [
            '@type' => 'City',
            'name' => $city,
            'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Emerald Coast, Florida'],
        ], $cities),
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Tideline Sites Services',
            'itemListElement' => [
                ['@type' => 'Offer', 'name' => 'Essential', 'price' => '299', 'priceCurrency' => 'USD',
                    'description' => 'Web design and SEO optimization, billed monthly.'],
                ['@type' => 'Offer', 'name' => 'Growth', 'price' => '499', 'priceCurrency' => 'USD',
                    'description' => 'Web design, SEO optimization, blog writing, and newsletters, billed monthly.'],
                ['@type' => 'Offer', 'name' => 'Build & Own', 'price' => '1000', 'priceCurrency' => 'USD',
                    'description' => 'One-time custom website build starting at $1,000, plus $20/month for hosting, SSL, backups, and security updates.'],
            ],
        ],
    ];

    $servicesSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Tideline Sites Services',
        'itemListElement' => array_map(fn ($i, $service) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'item' => [
                '@type' => 'Service',
                'name' => $service['name'],
                'description' => $service['description'],
                'provider' => ['@id' => $businessId],
                'areaServed' => ['@type' => 'AdministrativeArea', 'name' => 'Emerald Coast, Florida'],
            ],
        ], array_keys($services), $services),
    ];

    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ], $faqs),
    ];
@endphp

<x-layouts.marketing
    title="Tideline Sites — Web Design & SEO on the Emerald Coast"
    description="Beautiful, high-converting websites, SEO, blogs, and newsletters for local businesses from Destin to Panama City Beach. Plans from $299/month.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($servicesSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="absolute inset-0 -z-10"
             style="background:
                radial-gradient(60% 80% at 70% 0%, rgba(16,185,129,0.35), transparent 60%),
                radial-gradient(50% 60% at 0% 100%, rgba(15,118,110,0.35), transparent 60%),
                linear-gradient(180deg, #0b2a2e 0%, #0d4742 60%, #0b2a2e 100%);">
        </div>
        <img
            src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2400&q=70"
            alt="Aerial view of the Emerald Coast — turquoise Gulf water meeting white sand"
            width="2400" height="1600"
            class="absolute inset-0 -z-10 h-full w-full object-cover opacity-40 mix-blend-luminosity"
            loading="eager"
            fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-emerald-900)]/70 via-[var(--color-emerald-900)]/40 to-[var(--color-emerald-900)]/90"></div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-24 sm:pt-44 sm:pb-32 lg:px-8 lg:pt-52 lg:pb-40">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium tracking-wide text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-emerald-200)]"></span>
                    Emerald Coast · Destin to Panama City Beach
                </span>
                <h1 class="mt-6 font-serif text-5xl leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                    Emerald Coast
                    <span class="italic text-[var(--color-sand-200)]">Web Design &amp; SEO.</span>
                </h1>
                <p class="mt-6 max-w-xl font-serif text-2xl leading-snug text-white/90 sm:text-3xl">
                    Websites that win — from Destin to Panama City Beach.
                </p>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-white/75">
                    We design beautiful, fast websites and run the SEO, blogs, and newsletters that get local businesses found across the Emerald Coast.
                </p>
                <div class="mt-10 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <a href="#pricing"
                       class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg shadow-emerald-950/20 transition duration-200 ease-out hover:bg-[var(--color-sand-200)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-sand-200)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)]">
                        Start at $299/mo
                        <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#services"
                       class="inline-flex items-center gap-1 text-sm font-semibold text-white/80 underline-offset-4 transition hover:text-white hover:underline focus-visible:rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)]">
                        See what we do
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                {{-- Trust signals — concrete proof, not restated value props --}}
                <dl class="mt-14 grid grid-cols-2 gap-x-8 gap-y-5 border-t border-white/15 pt-8 text-sm {{ config('features.businesses_launched') ? 'sm:grid-cols-3' : '' }}">
                    @if (config('features.businesses_launched'))
                        <div>
                            <dt class="text-white/60">Local businesses launched</dt>
                            <dd class="mt-1 flex items-baseline gap-1 font-serif text-2xl text-white">
                                30<span class="text-base text-white/70">+</span>
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-white/60">Average launch</dt>
                        <dd class="mt-1 font-serif text-2xl text-white">1&ndash;2 weeks</dd>
                    </div>
                    <div>
                        <dt class="text-white/60">Husband &amp; wife team</dt>
                        <dd class="mt-1 font-serif text-2xl text-white">PCB based</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- subtle wave divider --}}
        <svg class="block w-full text-[var(--color-cream)]" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 40 C 240 80 480 0 720 40 S 1200 80 1440 40 L 1440 80 L 0 80 Z" />
        </svg>
    </section>

    {{-- ───────── Testimonials ─────────
         TODO: Replace placeholder quotes/attribution with real customer testimonials.
         Headshots: drop files in public/testimonials/ and update src paths below.
         The Stanford credibility stat lives at the bottom as a supporting line.
         Gated behind config('features.testimonials') — enable once we have real quotes. --}}
    @if (config('features.testimonials'))
    <section aria-label="What local owners are saying" class="bg-[var(--color-cream)] pt-20 sm:pt-24">
        <div class="mx-auto max-w-6xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Loved by local owners</p>
                <h2 class="mt-3 font-serif text-3xl tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                    Real businesses. Real results.
                </h2>
            </div>

            {{-- Hero quote --}}
            <figure class="relative mx-auto mt-12 max-w-3xl overflow-hidden rounded-3xl bg-white p-8 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-12 dark:bg-white/[0.04] dark:ring-white/10">
                <svg class="absolute -top-4 -left-2 h-24 w-24 text-[var(--color-emerald-600)]/10 sm:h-32 sm:w-32" viewBox="0 0 100 80" fill="currentColor" aria-hidden="true">
                    <path d="M0 80V40C0 18 18 0 40 0v16C26 16 16 26 16 40h24v40H0zm60 0V40C60 18 78 0 100 0v16C86 16 76 26 76 40h24v40H60z"/>
                </svg>
                <blockquote class="relative font-serif text-2xl leading-snug text-[var(--color-deep-teal)] italic sm:text-3xl">
                    “Our new site doubled inquiries in the first month. Jon and Elena get the Emerald Coast and they actually pick up the phone.”
                </blockquote>
                <figcaption class="relative mt-6 flex items-center gap-4">
                    <span class="grid h-12 w-12 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-lg text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">
                        SK
                    </span>
                    <div>
                        <p class="font-serif text-base text-[var(--color-deep-teal)]">Sarah Klein</p>
                        <p class="text-xs text-slate-600">Owner, Sandcastle Vacation Rentals · Destin</p>
                    </div>
                </figcaption>
            </figure>

            {{-- Supporting quotes --}}
            <div class="mx-auto mt-6 grid max-w-5xl gap-6 sm:grid-cols-2">
                <figure class="rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] dark:bg-white/[0.04] dark:ring-white/10">
                    <blockquote class="font-serif text-lg leading-snug text-[var(--color-deep-teal)] italic">
                        “We rank #1 for the keywords that actually bring in customers. It paid for itself in eight weeks.”
                    </blockquote>
                    <figcaption class="mt-4 flex items-center gap-3">
                        <span class="grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-sm text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">
                            MR
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-[var(--color-deep-teal)]">Marcus Reyes</p>
                            <p class="text-xs text-slate-600">Reyes HVAC · Panama City Beach</p>
                        </div>
                    </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] dark:bg-white/[0.04] dark:ring-white/10">
                    <blockquote class="font-serif text-lg leading-snug text-[var(--color-deep-teal)] italic">
                        “Three weeks from first call to a launched site I'm proud to send people to. No drama, no contracts.”
                    </blockquote>
                    <figcaption class="mt-4 flex items-center gap-3">
                        <span class="grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-sm text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">
                            JT
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-[var(--color-deep-teal)]">Jenna Thibault</p>
                            <p class="text-xs text-slate-600">30A Coastal Med Spa · Santa Rosa Beach</p>
                        </div>
                    </figcaption>
                </figure>
            </div>

            <p class="mt-8 text-center text-xs text-slate-500">
                Why design matters: <a href="https://dl.acm.org/doi/10.1145/997078.997097" target="_blank" rel="noopener" class="underline underline-offset-4 hover:text-[var(--color-emerald-700)]">46% of people judge a site's credibility on visual design alone</a> (Stanford, Fogg et al.).
            </p>
        </div>
    </section>
    @endif

    {{-- ───────── Services ───────── --}}
    <section id="services" class="bg-[var(--color-cream)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">What we do</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-[var(--color-deep-teal)] sm:text-5xl">
                    Web design, SEO, blogs &amp; newsletters.
                </h2>
                <p class="mt-5 text-lg leading-relaxed text-slate-600">
                    Everything your local business needs to look great online and get found by the people searching for you on the Emerald Coast.
                </p>
            </div>

            {{-- Foundation services — included in every plan --}}
            <div class="mx-auto mt-16 grid max-w-6xl gap-6 lg:grid-cols-2">
                @foreach (array_slice($services, 0, 2) as $service)
                    <article class="group relative flex flex-col overflow-hidden rounded-3xl border border-[var(--color-sand-300)]/60 bg-white p-8 shadow-[0_1px_0_rgba(11,42,46,0.04)] transition duration-200 ease-out hover:-translate-y-0.5 hover:border-[var(--color-emerald-600)]/30 hover:shadow-lg sm:p-10 dark:bg-white/[0.04] dark:border-white/10">
                        <span aria-hidden="true" class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-[var(--color-emerald-50)]/70 transition duration-300 group-hover:scale-110 dark:bg-white/[0.04]"></span>
                        <span class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-emerald-50)] text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-7 w-7" aria-hidden="true">
                                <path fill-rule="evenodd" d="{{ $service['icon'] }}" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <h3 class="relative mt-6 font-serif text-2xl text-[var(--color-deep-teal)] sm:text-3xl">{{ $service['name'] }}</h3>
                        <p class="relative mt-3 text-base leading-relaxed text-slate-600">{{ $service['description'] }}</p>
                        <p class="relative mt-6 text-xs font-medium uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Included in every plan</p>
                    </article>
                @endforeach
            </div>

            {{-- Growth add-ons --}}
            <div class="mx-auto mt-6 max-w-6xl">
                <div class="rounded-3xl border border-dashed border-[var(--color-emerald-600)]/30 bg-[var(--color-emerald-50)]/40 p-6 sm:p-8 dark:bg-white/[0.03] dark:border-white/10">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-baseline sm:justify-between">
                        <p class="font-serif text-lg text-[var(--color-deep-teal)]">
                            Also included with <span class="italic">Growth</span>
                        </p>
                        <a href="#pricing" class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)] focus-visible:rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50">
                            See Growth plan
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach (array_slice($services, 2, 2) as $service)
                            <article class="flex items-start gap-4 rounded-2xl bg-white p-5 ring-1 ring-[var(--color-sand-300)]/60 transition duration-200 ease-out hover:-translate-y-0.5 hover:ring-[var(--color-emerald-600)]/30 dark:bg-white/[0.04] dark:ring-white/10">
                                <span class="flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-[var(--color-emerald-50)] text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                        <path fill-rule="evenodd" d="{{ $service['icon'] }}" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-serif text-lg text-[var(--color-deep-teal)]">{{ $service['name'] }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $service['description'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Process ───────── --}}
    <section id="process" class="bg-[var(--color-sand-100)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">How it works</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-[var(--color-deep-teal)] sm:text-5xl">
                    Our process — four steps, no surprises.
                </h2>
            </div>

            {{-- Sequential timeline: horizontal on desktop with a connecting line through the step dots,
                 vertical on mobile with the line running down the left edge. --}}
            <ol class="mx-auto mt-16 max-w-6xl">
                <div class="relative lg:grid lg:grid-cols-4 lg:gap-8">
                    {{-- Connecting line, sits behind the step dots --}}
                    <span aria-hidden="true" class="absolute top-5 left-5 hidden h-px w-[calc(100%-2.5rem)] bg-gradient-to-r from-[var(--color-emerald-600)]/30 via-[var(--color-emerald-600)]/30 to-transparent lg:block"></span>
                    <span aria-hidden="true" class="absolute top-0 bottom-0 left-5 w-px bg-[var(--color-emerald-600)]/20 lg:hidden"></span>

                    @foreach ([
                        ['01', 'Discover', 'Week 1', 'A free 20-minute call to understand your business, your customers, and your goals.'],
                        ['02', 'Design', 'Week 1', 'We craft a custom design and walk you through every page before a single line of code is written.'],
                        ['03', 'Launch', 'Week 1–2', 'We build, test, and launch your new site — fast, mobile-ready, and SEO-optimized from day one.'],
                        ['04', 'Grow', 'Ongoing', 'Every month we sharpen your SEO and (on the Growth plan) publish fresh blogs and newsletters.'],
                    ] as [$num, $title, $duration, $copy])
                        <li class="relative flex gap-5 pb-10 last:pb-0 lg:flex-col lg:gap-0 lg:pb-0">
                            <span class="relative z-10 grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-600)] font-serif text-sm font-semibold text-white shadow-md ring-4 ring-[var(--color-sand-100)]">
                                {{ $num }}
                            </span>
                            <div class="lg:mt-6">
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">{{ $duration }}</p>
                                <h3 class="mt-1 font-serif text-xl text-[var(--color-deep-teal)] sm:text-2xl">{{ $title }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $copy }}</p>
                            </div>
                        </li>
                    @endforeach
                </div>
            </ol>
        </div>
    </section>

    {{-- ───────── Custom-built (not a CMS) ───────── --}}
    <section id="how-we-build" class="bg-[var(--color-cream)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-start gap-12 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">How we build</p>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-5xl">
                        Custom-built with Laravel — <span class="italic">not</span> WordPress, Wix, or Squarespace.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-700">
                        Every site we ship is a real web application — written from scratch in Laravel and engineered to grow with your business. That means we can do far more than a marketing site.
                    </p>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700">
                        Need a booking system that talks to your calendar? A CRM your team will actually use? A customer portal, intake form, or internal tool? We build it — on the same foundation as your website.
                    </p>
                    <p class="mt-8">
                        <a href="{{ route('contact.show') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)]">
                            Talk through a custom project
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </p>
                </div>

                <div class="lg:col-span-7">
                    <div class="rounded-3xl bg-white p-8 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-10 dark:bg-white/[0.04] dark:ring-white/10">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">What we can build</p>
                        <ul class="mt-6 grid gap-x-6 gap-y-4 text-sm text-slate-700 sm:grid-cols-2">
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Booking &amp; scheduling systems</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Customer relationship tools (CRM)</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Customer &amp; client portals</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Inventory &amp; job tracking</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Quoting &amp; intake workflows</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Payment &amp; subscription flows</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />Internal dashboards &amp; reports</li>
                            <li class="flex gap-3"><x-marketing.check color="emerald" />API &amp; third-party integrations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Recent work ───────── --}}
    @php
        $projects = [
            [
                'name' => 'Venture',
                'url' => 'https://learnwithventure.com',
                'tagline' => 'Personalized K-12 learning platform',
                'description' => 'A curiosity-led homeschooling platform that builds personalized K-12 learning paths for families. Built with Laravel, Inertia, and Stripe billing.',
                'image' => 'projects/venture.png',
                'tags' => ['Laravel', 'Inertia', 'Stripe', 'AI'],
            ],
            [
                'name' => 'Wordsmith',
                'url' => 'https://usewordsmith.com',
                'tagline' => 'AI-assisted social media planner',
                'description' => 'A social media content planning tool with AI-assisted writing — schedule, draft, and refine posts in one place.',
                'image' => 'projects/wordsmith.png',
                'tags' => ['Laravel', 'AI', 'Scheduling'],
            ],
        ];
    @endphp
    <section id="work" class="bg-[var(--color-sand-100)] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Recent work</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-[var(--color-deep-teal)] sm:text-5xl">
                    Real products we've shipped.
                </h2>
                <p class="mt-5 text-lg leading-relaxed text-slate-600">
                    A peek at what we build when the brief goes beyond a marketing site — full web applications, custom-built from the ground up.
                </p>
            </div>

            <div class="mx-auto mt-16 grid max-w-6xl gap-8 lg:grid-cols-2">
                @foreach ($projects as $project)
                    <article class="group flex flex-col overflow-hidden rounded-3xl bg-white ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] transition duration-200 ease-out hover:-translate-y-1 hover:shadow-xl dark:bg-white/[0.04] dark:ring-white/10">
                        {{-- Browser chrome mockup --}}
                        <div class="border-b border-[var(--color-sand-300)]/60 bg-[var(--color-cream)] px-4 py-3 dark:bg-white/[0.03] dark:border-white/10">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#FF5F57]"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-[#FEBC2E]"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-[#28C840]"></span>
                                <span class="ml-3 truncate rounded-md bg-white px-3 py-1 text-xs text-slate-500 ring-1 ring-[var(--color-sand-300)]/70 dark:bg-white/10 dark:text-white/70 dark:ring-white/10">
                                    {{ parse_url($project['url'], PHP_URL_HOST) }}
                                </span>
                            </div>
                        </div>

                        {{-- Screenshot --}}
                        <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="relative block aspect-[16/10] overflow-hidden bg-gradient-to-br from-[var(--color-emerald-50)] to-[var(--color-sand-200)] dark:from-white/[0.04] dark:to-white/[0.02]">
                            <img
                                src="{{ asset($project['image']) }}"
                                alt="Screenshot of {{ $project['name'] }} — {{ $project['tagline'] }}"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover object-top transition duration-300 group-hover:scale-[1.02]"
                                onerror="this.style.display='none'">
                        </a>

                        {{-- Body --}}
                        <div class="flex flex-1 flex-col gap-4 p-8">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">{{ $project['tagline'] }}</p>
                                <h3 class="mt-2 font-serif text-2xl text-[var(--color-deep-teal)] sm:text-3xl">{{ $project['name'] }}</h3>
                            </div>
                            <p class="text-base leading-relaxed text-slate-600">{{ $project['description'] }}</p>

                            <ul class="flex flex-wrap gap-2 text-xs">
                                @foreach ($project['tags'] as $tag)
                                    <li class="rounded-full bg-[var(--color-emerald-50)] px-2.5 py-1 font-medium text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">{{ $tag }}</li>
                                @endforeach
                            </ul>

                            <div class="mt-auto pt-2">
                                <a href="{{ $project['url'] }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)] focus-visible:rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50">
                                    Visit {{ $project['name'] }}
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ───────── About teaser ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <figure class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                        <img src="{{ asset('team/jon-elena.jpeg') }}"
                             alt="Jon and Elena Ringeisen, co-founders of Tideline Sites"
                             width="1200" height="900"
                             loading="lazy"
                             class="block h-auto w-full">
                    </figure>
                </div>
                <div class="lg:col-span-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Meet the team</p>
                    <h2 class="mt-3 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                        <span class="italic">Family and Veteran owned</span> — built on the Emerald Coast.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-700">
                        Tideline Sites is Jon and Elena — a husband-and-wife team based in Panama City Beach. Jon writes the code; Elena runs the strategy. When you hire us, you get the two people who actually do the work.
                    </p>
                    <ul class="mt-6 flex flex-wrap gap-2 text-sm">
                        <li class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">Husband &amp; wife team</li>
                        <li class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">US Army veteran</li>
                        <li class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">15 yrs engineering</li>
                    </ul>
                    <p class="mt-8">
                        <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)]">
                            Read our story
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Service Area ───────── --}}
    <section id="service-area" class="relative overflow-hidden bg-[var(--color-sand-100)] py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Service area</p>
                    <h2 class="mt-3 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                        Emerald Coast web design &amp; SEO, from Destin to Panama City Beach.
                    </h2>
                    <p class="mt-5 text-base leading-relaxed text-slate-600">
                        We're locals — and we build every site to win in the towns it serves.
                    </p>
                    <dl class="mt-6 grid grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Towns served</dt>
                            <dd class="mt-1 font-serif text-3xl text-[var(--color-deep-teal)]">{{ count($cities) }}+</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Miles of coast</dt>
                            <dd class="mt-1 font-serif text-3xl text-[var(--color-deep-teal)]">~50</dd>
                        </div>
                    </dl>
                    <a href="{{ route('service-area') }}" class="mt-6 inline-flex items-center text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)]">
                        See full service area
                        <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <div class="lg:col-span-7">
                    <div class="relative rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10">
                        <x-marketing.coastline />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Pricing ───────── --}}
    <section id="pricing" class="relative bg-[var(--color-deep-teal)] py-24 text-white sm:py-32">
        <div class="absolute inset-0 -z-10 opacity-30"
             style="background: radial-gradient(60% 60% at 80% 0%, rgba(16,185,129,0.35), transparent 60%), radial-gradient(50% 50% at 10% 100%, rgba(245,233,215,0.15), transparent 60%);">
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-200)]">Pricing</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight sm:text-5xl">Pricing that fits how you work.</h2>
                <p class="mt-3 font-serif text-2xl italic text-[var(--color-sand-200)]/90">Monthly plans or a one-time build — no contracts.</p>
                <p class="mt-5 text-lg leading-relaxed text-white/75">
                    Pick the plan that fits. Cancel anytime — your site is always yours.
                </p>
            </div>

            <div class="mx-auto mt-24 grid max-w-6xl gap-6 lg:grid-cols-3 sm:mt-28">
                {{-- Essential --}}
                <article class="flex flex-col rounded-3xl bg-white/[0.04] p-8 ring-1 ring-white/15 backdrop-blur">
                    <div class="flex items-baseline justify-between">
                        <h3 class="font-serif text-2xl">Essential</h3>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80">Get found</span>
                    </div>
                    <p class="mt-2 text-sm text-white/70">Get a beautiful website and start ranking locally.</p>
                    <p class="mt-6">
                        <span class="font-serif text-5xl">$299</span>
                        <span class="ml-1 text-sm text-white/70">/month</span>
                    </p>
                    <ul class="mt-8 space-y-3 text-sm text-white/85">
                        <li class="flex gap-3"><x-marketing.check />Custom web design</li>
                        <li class="flex gap-3"><x-marketing.check />Mobile-optimized & fast</li>
                        <li class="flex gap-3"><x-marketing.check />On-page SEO setup</li>
                        <li class="flex gap-3"><x-marketing.check />Local listings (Google, Bing, Apple)</li>
                        <li class="flex gap-3"><x-marketing.check />Google Search Console setup</li>
                        <li class="flex gap-3"><x-marketing.check />Monthly SEO optimization</li>
                        <li class="flex gap-3"><x-marketing.check />Hosting, SSL, backups</li>
                    </ul>
                    <a href="{{ route('contact.show', ['plan' => 'essential']) }}"
                       class="mt-10 inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white transition duration-200 ease-out hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-deep-teal)]">
                        Start with Essential
                    </a>
                </article>

                {{-- Growth (popular) --}}
                <article class="keep-light relative z-10 flex flex-col rounded-3xl bg-white p-8 text-[var(--color-deep-teal)] shadow-2xl shadow-emerald-950/40 ring-1 ring-white/30 lg:-my-6 lg:scale-105 lg:p-10">
                    <span class="absolute -top-4 left-1/2 inline-flex -translate-x-1/2 items-center gap-1.5 rounded-full bg-[var(--color-emerald-600)] px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.14em] text-white shadow-lg shadow-emerald-950/30 ring-2 ring-white">
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.96a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.364 1.118l1.287 3.96c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.366 2.446c-.785.57-1.84-.197-1.54-1.118l1.287-3.96a1 1 0 00-.364-1.118L2.65 9.247c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.96z" />
                        </svg>
                        Most popular
                    </span>
                    <div class="flex items-baseline justify-between">
                        <h3 class="font-serif text-2xl lg:text-3xl">Growth</h3>
                        <span class="rounded-full bg-[var(--color-emerald-50)] px-3 py-1 text-xs font-medium text-[var(--color-emerald-700)]">Get found & stay top of mind</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Everything in Essential, plus the content that drives long-term growth.</p>
                    <p class="mt-6">
                        <span class="font-serif text-5xl lg:text-6xl">$499</span>
                        <span class="ml-1 text-sm text-slate-500">/month</span>
                    </p>
                    <ul class="mt-8 space-y-3 text-sm text-slate-700">
                        <li class="flex gap-3"><x-marketing.check color="emerald" />Everything in Essential</li>
                        <li class="flex gap-3"><x-marketing.check color="emerald" />2 original blog posts / month</li>
                        <li class="flex gap-3"><x-marketing.check color="emerald" />Weekly customer newsletter</li>
                        <li class="flex gap-3"><x-marketing.check color="emerald" />Google Analytics</li>
                        <li class="flex gap-3"><x-marketing.check color="emerald" />Priority support</li>
                        <li class="flex gap-3"><x-marketing.check color="emerald" />Quarterly performance report</li>
                    </ul>
                    <a href="{{ route('contact.show', ['plan' => 'growth']) }}"
                       class="mt-10 inline-flex items-center justify-center rounded-full bg-[var(--color-emerald-600)] px-6 py-3 text-sm font-semibold text-white shadow transition duration-200 ease-out hover:bg-[var(--color-emerald-700)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)] focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                        Start with Growth
                    </a>
                </article>

                {{-- Build & Own (one-time) --}}
                <article class="flex flex-col rounded-3xl bg-white/[0.04] p-8 ring-1 ring-white/15 backdrop-blur">
                    <div class="flex items-baseline justify-between">
                        <h3 class="font-serif text-2xl">Build &amp; Own</h3>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80">One-time build</span>
                    </div>
                    <p class="mt-2 text-sm text-white/70">For owners who'd rather not be on a managed plan.</p>
                    <p class="mt-6">
                        <span class="font-serif text-5xl">$1,000+</span>
                        <span class="ml-1 text-sm text-white/70">/one-time</span>
                    </p>
                    <p class="mt-1 text-sm text-white/70">+ $20/month hosting, SSL, backups &amp; security updates</p>
                    <ul class="mt-8 space-y-3 text-sm text-white/85">
                        <li class="flex gap-3"><x-marketing.check />Everything in Essential</li>
                        <li class="flex gap-3"><x-marketing.check />Google Analytics</li>
                        <li class="flex gap-3"><x-marketing.check />One-time build, then it's yours</li>
                        <li class="flex gap-3"><x-marketing.check />Hosting, SSL, backups &amp; security updates</li>
                    </ul>
                    <a href="{{ route('contact.show') }}"
                       class="mt-10 inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white transition duration-200 ease-out hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-deep-teal)]">
                        Contact for pricing
                    </a>
                </article>
            </div>

            <p class="mt-10 text-center text-sm text-white/60">
                Need something custom? <a href="{{ route('contact.show') }}" class="underline underline-offset-4 hover:text-white">Get in touch</a>.
            </p>
        </div>
    </section>

    {{-- ───────── FAQ ───────── --}}
    <section id="faq" class="bg-[var(--color-cream)] py-24 sm:py-32">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">FAQ</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-[var(--color-deep-teal)] sm:text-5xl">
                    Frequently asked questions.
                </h2>
            </div>

            <dl class="mt-14 divide-y divide-[var(--color-sand-300)]/70 border-y border-[var(--color-sand-300)]/70">
                @foreach ($faqs as $faq)
                    <details class="faq-item group py-6">
                        <summary class="-mx-2 flex cursor-pointer list-none items-start justify-between gap-6 rounded-xl px-2 py-1 transition duration-200 hover:bg-[var(--color-sand-200)]/30 focus-visible:bg-[var(--color-sand-200)]/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/40">
                            <dt class="font-serif text-lg text-[var(--color-deep-teal)] group-hover:text-[var(--color-emerald-800)] sm:text-xl">{{ $faq['question'] }}</dt>
                            <span class="mt-1 grid h-8 w-8 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] text-[var(--color-emerald-700)] transition duration-300 ease-out group-open:rotate-45 group-open:bg-[var(--color-emerald-600)] group-open:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 4a.75.75 0 01.75.75v4.5h4.5a.75.75 0 010 1.5h-4.5v4.5a.75.75 0 01-1.5 0v-4.5h-4.5a.75.75 0 010-1.5h4.5v-4.5A.75.75 0 0110 4z" />
                                </svg>
                            </span>
                        </summary>
                        <dd class="faq-answer mt-3 pr-12 text-base leading-relaxed text-slate-600">{{ $faq['answer'] }}</dd>
                    </details>
                @endforeach
            </dl>

            <p class="mt-10 text-center text-sm text-slate-600">
                Still have questions?
                <a href="{{ route('contact.show') }}" class="inline-flex items-center gap-1 font-semibold text-[var(--color-emerald-700)] underline-offset-4 hover:underline hover:text-[var(--color-emerald-800)] focus-visible:rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50">
                    Talk to us
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
    </section>

    {{-- ───────── Final CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] py-24 text-white sm:py-32">
        <div class="absolute inset-0 -z-10 opacity-50"
             style="background: radial-gradient(50% 60% at 50% 100%, rgba(16,185,129,0.4), transparent 60%);">
        </div>
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-4xl tracking-tight sm:text-5xl">
                Ready to make a splash?
            </h2>
            <p class="mt-5 text-lg text-white/80">
                Pick a plan and we'll have your new site live in weeks, not months.
            </p>
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row sm:gap-6">
                <a href="{{ route('contact.show', ['plan' => 'growth']) }}"
                   class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg shadow-emerald-950/30 transition duration-200 ease-out hover:bg-[var(--color-sand-200)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-sand-200)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)]">
                    Start with Growth — $499/mo
                </a>
                <a href="{{ route('contact.show') }}"
                   class="inline-flex items-center gap-1 text-sm font-semibold text-white/80 underline-offset-4 transition hover:text-white hover:underline focus-visible:rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)]">
                    Not sure yet? Book a free 15-min call
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
