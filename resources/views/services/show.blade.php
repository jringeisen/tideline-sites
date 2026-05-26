@php
    /** @var \App\Models\Service $service */
    $title = $service->meta_title ?: "{$service->name} — All American Web Design";
    $description = $service->meta_description ?: ($service->summary ?: "Professional {$service->name} for American small businesses.");
    $canonical = route('services.show', $service->slug);
    $ogImage = $service->og_image_url ?: asset('og-image.png');

    $businessId = url('/') . '#business';
    $faqs = collect($service->faqs ?? [])->filter(fn ($faq) => ! empty($faq['question']) && ! empty($faq['answer']))->values();

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->name,
        'description' => $description,
        'url' => $canonical,
        'provider' => ['@id' => $businessId],
        'areaServed' => $service->locations->isNotEmpty()
            ? $service->locations->map(fn ($location) => ['@type' => 'City', 'name' => $location->name])->all()
            : ['@type' => 'Country', 'name' => 'United States'],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => route('services.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $service->name, 'item' => $canonical],
        ],
    ];

    $faqSchema = $faqs->isNotEmpty() ? [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ])->all(),
    ] : null;
@endphp

<x-layouts.marketing :title="$title" :description="$description" :canonical="$canonical" :ogImage="$ogImage">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @if ($faqSchema)
            <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endif
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
                    <li><a href="{{ route('services.index') }}" class="hover:text-white">Services</a></li>
                    <li aria-hidden="true">›</li>
                    <li class="text-white/85">{{ $service->name }}</li>
                </ol>
            </nav>
            <div class="max-w-3xl">
                <h1 class="font-serif text-5xl font-bold uppercase leading-[0.95] tracking-tight sm:text-6xl lg:text-7xl">
                    {{ $service->name }}
                </h1>
                @if ($service->hero_subhead)
                    <p class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl">{{ $service->hero_subhead }}</p>
                @endif
                <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact.show') }}"
                       class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-black/30 transition hover:bg-[var(--color-red-deep)]">
                        Get started
                    </a>
                    <a href="{{ route('services.index') }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-white/10">
                        All services
                    </a>
                </div>
            </div>
        </div>

        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
    </section>

    {{-- ───────── Body ───────── --}}
    @if ($service->body)
        <section class="bg-[var(--color-cream)] py-16 sm:py-24">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="prose prose-slate max-w-none prose-headings:font-serif prose-headings:text-[var(--color-deep-teal)] prose-a:text-[var(--color-emerald-700)] prose-img:rounded-xl dark:prose-invert">
                    {!! $service->body !!}
                </div>
            </div>
        </section>
    @elseif ($service->summary)
        <section class="bg-[var(--color-cream)] py-16 sm:py-24">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <p class="text-lg leading-relaxed text-slate-700 dark:text-white/80">{{ $service->summary }}</p>
            </div>
        </section>
    @endif

    {{-- ───────── FAQs ───────── --}}
    @if ($faqs->isNotEmpty())
        <section class="bg-[var(--color-sand-100)] py-16 sm:py-24">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Questions</p>
                <h2 class="mt-3 font-serif text-3xl font-bold uppercase tracking-tight text-[var(--color-deep-teal)] sm:text-4xl">
                    {{ $service->name }} FAQs
                </h2>
                <dl class="mt-10 space-y-6">
                    @foreach ($faqs as $faq)
                        <div class="rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10">
                            <dt class="font-serif text-lg text-[var(--color-deep-teal)]">{{ $faq['question'] }}</dt>
                            <dd class="mt-2 leading-relaxed text-slate-600 dark:text-white/70">{{ $faq['answer'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>
    @endif

    {{-- ───────── Serving these towns ───────── --}}
    @if ($service->locations->isNotEmpty())
        <section class="bg-[var(--color-cream)] py-16">
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-red)]">Where we work</p>
                <h2 class="mt-3 font-serif text-2xl font-bold uppercase text-[var(--color-deep-teal)]">
                    {{ $service->name }} across the Gulf Coast
                </h2>
                <ul class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
                    @foreach ($service->locations as $location)
                        <li><a href="{{ route('location.show', $location->slug) }}"
                               class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-emerald-50)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15 dark:hover:bg-white/15">{{ $location->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ───────── Final CTA ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] py-20 text-white sm:py-28">
        <div class="absolute inset-0 -z-10 opacity-50"
             style="background: radial-gradient(50% 60% at 50% 100%, rgba(59,83,120,0.5), transparent 60%);"></div>
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
            <h2 class="font-serif text-3xl font-bold uppercase tracking-tight sm:text-4xl">Ready to get started?</h2>
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
