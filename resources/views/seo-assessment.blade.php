@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Free SEO Assessment — Tideline Sites',
        'url' => url()->current(),
        'description' => 'Request a free SEO assessment for your business website. We\'ll review your site and send a personalized action plan.',
        'mainEntity' => [
            '@type' => 'Service',
            'name' => 'Free SEO Assessment',
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => 'Tideline Sites',
                'email' => 'hello@tidelinesites.com',
                'areaServed' => 'Emerald Coast, Florida',
            ],
        ],
    ];

    $benefits = [
        [
            'title' => 'On-page audit',
            'body' => 'A page-by-page look at titles, meta, headings, internal links, and content depth.',
        ],
        [
            'title' => 'Keyword opportunities',
            'body' => 'Search terms your customers are using that you could realistically rank for.',
        ],
        [
            'title' => 'Competitor snapshot',
            'body' => 'How your site stacks up against the businesses you compete with locally.',
        ],
        [
            'title' => 'Action plan',
            'body' => 'A short, prioritized list of fixes that move the needle — no fluff.',
        ],
    ];
@endphp

<x-layouts.marketing
    title="Free SEO Assessment — Tideline Sites"
    description="See where your website stands. Request a free SEO assessment and we'll send back a personalized action plan within two business days.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero band ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="absolute inset-0 -z-10"
             style="background:
                radial-gradient(50% 70% at 80% 0%, rgba(16,185,129,0.30), transparent 60%),
                radial-gradient(50% 60% at 0% 100%, rgba(15,118,110,0.30), transparent 60%),
                linear-gradient(180deg, #0b2a2e 0%, #0d4742 100%);">
        </div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium tracking-wide text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-emerald-200)]"></span>
                    100% free · No strings
                </span>
                <h1 class="mt-6 font-serif text-5xl leading-[1.05] tracking-tight sm:text-6xl">
                    See where your site <span class="italic text-[var(--color-sand-200)]">stands.</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                    Tell us a little about your business and we'll send back a personalized SEO assessment — what's working, what's not, and what to fix first.
                </p>
            </div>
        </div>

        <svg class="block w-full text-[var(--color-cream)]" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 40 C 240 80 480 0 720 40 S 1200 80 1440 40 L 1440 80 L 0 80 Z" />
        </svg>
    </section>

    {{-- ───────── Form + benefits ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12">
                {{-- Form column --}}
                <div class="lg:col-span-7">
                    @if (session('status'))
                        <div role="status"
                             class="mb-8 flex items-start gap-3 rounded-2xl border border-[var(--color-emerald-600)]/25 bg-[var(--color-emerald-50)] px-5 py-4 text-sm text-[var(--color-emerald-800)]">
                            <svg class="mt-0.5 h-5 w-5 flex-none text-[var(--color-emerald-600)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seo-assessment.store') }}" novalidate
                          class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10">
                        @csrf

                        {{-- Anti-spam: honeypot + encrypted render timestamp. --}}
                        <div aria-hidden="true" class="pointer-events-none absolute -left-[10000px] h-px w-px overflow-hidden opacity-0">
                            <label for="company_url">Company URL (leave blank)</label>
                            <input type="text" name="company_url" id="company_url" tabindex="-1" autocomplete="off" value="">
                        </div>
                        <input type="hidden" name="started_at" value="{{ Crypt::encryptString(now()->timestamp) }}">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-[var(--color-deep-teal)]">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('name') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-[var(--color-deep-teal)]">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('email') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('email')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="business_name" class="block text-sm font-medium text-[var(--color-deep-teal)]">Business name</label>
                                <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" required autocomplete="organization"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('business_name') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('business_name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="website" class="block text-sm font-medium text-[var(--color-deep-teal)]">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website') }}" required autocomplete="url" placeholder="https://"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('website') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('website')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-7 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                            <p class="text-xs text-slate-500">No credit card. No sales pitch. Just useful feedback.</p>
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-emerald-600)] px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-[var(--color-emerald-700)] sm:w-auto">
                                Request my assessment
                                <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Benefits column --}}
                <aside class="lg:col-span-5">
                    <div class="rounded-3xl bg-[var(--color-emerald-900)] p-8 text-white">
                        <h2 class="font-serif text-2xl">What you'll receive</h2>
                        <p class="mt-2 text-sm text-white/75">A short, plainspoken report you can act on — usually back within two business days.</p>

                        <ul class="mt-8 space-y-6 text-sm">
                            @foreach ($benefits as $benefit)
                                <li class="flex items-start gap-4">
                                    <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 5.29a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 111.06-1.06l2.97 2.97 6.97-6.97a.75.75 0 011.06 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-medium text-white">{{ $benefit['title'] }}</p>
                                        <p class="mt-0.5 text-white/75">{{ $benefit['body'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

</x-layouts.marketing>
