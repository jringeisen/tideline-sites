@php
    $businessId = url('/') . '#business';
    $pageUrl = url()->current();

    $jonSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        '@id' => $pageUrl . '#jon',
        'name' => 'Jon Ringeisen',
        'jobTitle' => 'Co-founder & Lead Developer',
        'worksFor' => ['@id' => $businessId],
        'image' => asset('team/jon-elena.jpeg'),
        'description' => 'Co-founder and lead developer at Tideline Sites. 15 years as a software engineer, one SaaS exit, and a US Army veteran.',
        'knowsAbout' => ['Web development', 'SaaS engineering', 'SEO', 'Laravel', 'Vue.js'],
        'alumniOf' => [
            '@type' => 'Organization',
            'name' => 'United States Army',
        ],
        'hasOccupation' => [
            [
                '@type' => 'Occupation',
                'name' => 'Software Engineer',
                'description' => '15 years building software and SaaS products.',
            ],
            [
                '@type' => 'Occupation',
                'name' => 'Intelligence Analyst (35F), Sergeant',
                'description' => 'United States Army. Eight years of service, two combat deployments (Mosul, Iraq and Kuwait).',
            ],
        ],
    ];

    $elenaSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        '@id' => $pageUrl . '#elena',
        'name' => 'Elena Ringeisen',
        'jobTitle' => 'Co-founder, Marketing & Sales',
        'worksFor' => ['@id' => $businessId],
        'image' => asset('team/jon-elena.jpeg'),
        'description' => 'Co-founder of Tideline Sites. Entrepreneur, marketer, and a homeschooling mother of four.',
        'knowsAbout' => ['Marketing', 'Sales', 'Customer success', 'Small business growth'],
        'spouse' => ['@id' => $pageUrl . '#jon'],
    ];

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $businessId,
        'name' => 'Tideline Sites',
        'url' => url('/'),
        'logo' => asset('og-image.jpg'),
        'description' => 'A husband-and-wife, veteran-owned web design and SEO studio serving the Emerald Coast from Panama City Beach.',
        'foundingLocation' => ['@type' => 'Place', 'name' => 'Panama City Beach, Florida'],
        'founder' => [
            ['@id' => $pageUrl . '#jon'],
            ['@id' => $pageUrl . '#elena'],
        ],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => $pageUrl],
        ],
    ];
@endphp

<x-layouts.marketing
    title="About Tideline Sites — Family-owned & Veteran-owned"
    description="Tideline Sites is a husband-and-wife team and a veteran-owned web design and SEO studio on the Emerald Coast. Meet Jon and Elena.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($jonSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($elenaSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="absolute inset-0 -z-10"
             style="background:
                radial-gradient(50% 70% at 80% 0%, rgba(16,185,129,0.30), transparent 60%),
                radial-gradient(50% 60% at 0% 100%, rgba(15,118,110,0.30), transparent 60%),
                linear-gradient(180deg, #0b2a2e 0%, #0d4742 100%);">
        </div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <nav aria-label="Breadcrumb" class="mb-6 text-sm text-white/60">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li aria-hidden="true">›</li>
                    <li class="text-white/85">About</li>
                </ol>
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium tracking-wide text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-emerald-200)]"></span>
                    About Tideline Sites
                </span>
                <h1 class="mt-6 font-serif text-5xl leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                    Family-owned. <span class="italic text-[var(--color-sand-200)]">Veteran-owned.</span>
                </h1>
                <p class="mt-6 max-w-2xl font-serif text-2xl leading-snug text-white/90 sm:text-3xl">
                    A husband-and-wife team building beautiful websites for Emerald Coast businesses.
                </p>
            </div>
        </div>

        <svg class="block w-full text-[var(--color-cream)]" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 40 C 240 80 480 0 720 40 S 1200 80 1440 40 L 1440 80 L 0 80 Z" />
        </svg>
    </section>

    {{-- ───────── Story ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Our story</p>
            <h2 class="mt-3 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                Built by two people who care about the work.
            </h2>
            <div class="mt-6 space-y-4 text-lg leading-relaxed text-slate-700">
                <p>
                    Tideline Sites is a husband-and-wife team based in Panama City Beach. We started it for a simple reason: we'd watched too many Emerald Coast businesses get stuck with cookie-cutter websites, ghosted by their agencies, and locked into contracts that didn't serve them.
                </p>
                <p>
                    We do things differently. Jon writes the code. Elena handles the strategy. There's no junior account manager bouncing your emails to a third-party developer in another time zone — when you hire Tideline, you get the two people who actually do the work.
                </p>
                <p>
                    We're proud to be family-owned and veteran-owned, and we're proud to be locals. We're not going anywhere — and neither is your website.
                </p>
            </div>
        </div>
    </section>

    {{-- ───────── Bios ───────── --}}
    <section class="bg-[var(--color-sand-100)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <figure class="lg:col-span-6">
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                        <img src="{{ asset('team/jon-elena.jpeg') }}"
                             alt="Jon and Elena Ringeisen, co-founders of Tideline Sites"
                             width="1200" height="900"
                             loading="lazy"
                             class="block h-auto w-full">
                    </div>
                    <figcaption class="mt-3 text-center text-xs text-slate-500">Jon &amp; Elena Ringeisen, co-founders</figcaption>
                </figure>

                <div class="space-y-8 lg:col-span-6">
                    <article>
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <h3 class="font-serif text-2xl text-[var(--color-deep-teal)]">Jon Ringeisen</h3>
                            <span class="text-sm font-medium uppercase tracking-wider text-[var(--color-emerald-700)]">Co-founder &amp; Lead Developer</span>
                        </div>
                        <p class="mt-3 text-base leading-relaxed text-slate-700">
                            Jon has been a software engineer for 15 years, with a string of SaaS products to his name and one successful exit behind him. Before software, he served eight years in the U.S. Army as a paratrooper and 35F intelligence analyst, finishing his enlistment as a Sergeant with two combat deployments — Mosul, Iraq and Kuwait. At Tideline he writes the code, runs the SEO, and sweats the details that decide whether your site loads fast and ranks well.
                        </p>
                        <ul class="mt-4 flex flex-wrap gap-2 text-xs">
                            <li class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">U.S. Army Veteran</li>
                            <li class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">15 yrs engineering</li>
                        </ul>
                    </article>

                    <article>
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <h3 class="font-serif text-2xl text-[var(--color-deep-teal)]">Elena Ringeisen</h3>
                            <span class="text-sm font-medium uppercase tracking-wider text-[var(--color-emerald-700)]">Co-founder, Marketing &amp; Sales</span>
                        </div>
                        <p class="mt-3 text-base leading-relaxed text-slate-700">
                            Elena is an entrepreneur, a homeschooling mom of four, and the marketing and sales mind behind Tideline. She knows how to talk to small business owners because she is one, and she's run enough of her own ventures to know what it takes to grow a brand from zero. She handles the conversations, the strategy, and the customer side of the business — and somehow still teaches four kids long division.
                        </p>
                        <ul class="mt-4 flex flex-wrap gap-2 text-xs">
                            <li class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">Marketing &amp; Sales</li>
                            <li class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">Mom of 4</li>
                            <li class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15">Entrepreneur</li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────── Values ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">What we stand for</p>
                <h2 class="mt-3 font-serif text-3xl tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                    Three things we never compromise on.
                </h2>
            </div>

            <div class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-3">
                @foreach ([
                    ['Family-owned', 'A husband-and-wife team that has skin in the game on every project. Your success is our family\'s livelihood.'],
                    ['Veteran-owned', 'Eight years in the US Army taught Jon that discipline and follow-through aren\'t optional. We bring the same standard to your website.'],
                    ['Locally rooted', 'We live on the Emerald Coast. We know the businesses, the seasons, and the searches your customers are running.'],
                ] as [$title, $copy])
                    <article class="rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                        <h3 class="font-serif text-xl text-[var(--color-deep-teal)]">{{ $title }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ───────── CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] py-20 text-white sm:py-28">
        <div class="absolute inset-0 -z-10 opacity-50"
             style="background: radial-gradient(50% 60% at 50% 100%, rgba(16,185,129,0.4), transparent 60%);">
        </div>
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-3xl tracking-tight sm:text-4xl">
                Want to work with us?
            </h2>
            <p class="mt-4 text-lg text-white/80">
                Tell us about your business. We'll get back to you within one business day — from one real person, not a sales bot.
            </p>
            <div class="mt-8">
                <a href="{{ route('contact.show') }}"
                   class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg transition hover:bg-[var(--color-sand-200)]">
                    Get in touch
                </a>
            </div>
        </div>
    </section>

</x-layouts.marketing>
