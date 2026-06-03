<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CheckIcon from '@/components/marketing/CheckIcon.vue';

type Service = { name: string; description: string; icon: string };
type Faq = { question: string; answer: string };

const props = defineProps<{
    services: Service[];
    faqs: Faq[];
    features: { testimonials: boolean; businessesLaunched: boolean };
}>();

const page = usePage();

const foundationServices = computed(() => props.services.slice(0, 2));
const growthServices = computed(() => props.services.slice(2, 4));

const processSteps: Array<[string, string, string, string]> = [
    [
        '01',
        'Discover',
        'Week 1',
        'A free 20-minute call to understand your business, your customers, and your goals.',
    ],
    [
        '02',
        'Design',
        'Week 1',
        'We craft a custom design and walk you through every page before a single line of code is written.',
    ],
    [
        '03',
        'Launch',
        'Week 1–2',
        'We build, test, and launch your new site. Fast, mobile-ready, and SEO-optimized from day one.',
    ],
    [
        '04',
        'Grow',
        'Ongoing',
        'Every month we sharpen your SEO and (on the Growth plan) publish fresh blogs and newsletters.',
    ],
];

const buildCapabilities = [
    'Booking & scheduling systems',
    'Customer relationship tools (CRM)',
    'Customer & client portals',
    'Inventory & job tracking',
    'Quoting & intake workflows',
    'Payment & subscription flows',
    'Internal dashboards & reports',
    'API & third-party integrations',
];

const projects = [
    {
        name: 'Venture',
        url: 'https://learnwithventure.com',
        host: 'learnwithventure.com',
        tagline: 'Personalized K-12 learning platform',
        description:
            'A curiosity-led homeschooling platform that builds personalized K-12 learning paths for families. Built with Laravel, Inertia, and Stripe billing.',
        image: '/projects/venture-1200.webp',
        srcset: '/projects/venture-600.webp 600w, /projects/venture-1200.webp 1200w',
        width: 1200,
        height: 683,
        tags: ['Laravel', 'Inertia', 'Stripe', 'AI'],
    },
    {
        name: 'Wordsmith',
        url: 'https://usewordsmith.com',
        host: 'usewordsmith.com',
        tagline: 'AI-assisted social media planner',
        description:
            'A social media content planning tool with AI-assisted writing. Schedule, draft, and refine posts in one place.',
        image: '/projects/wordsmith-1200.webp',
        srcset: '/projects/wordsmith-600.webp 600w, /projects/wordsmith-1200.webp 1200w',
        width: 1200,
        height: 671,
        tags: ['Laravel', 'AI', 'Scheduling'],
    },
];

// Veteran pricing toggle — initialise from ?veteran=1 so SSR renders it pre-toggled
// when linked from the veteran banner.
const initialVeteran = (() => {
    const queryIndex = page.url.indexOf('?');

    if (queryIndex === -1) {
        return false;
    }

    return (
        new URLSearchParams(page.url.slice(queryIndex)).get('veteran') === '1'
    );
})();

const veteran = ref(initialVeteran);

const contactHref = (plan?: string): string => {
    const params = new URLSearchParams();

    if (plan) {
        params.set('plan', plan);
    }

    if (veteran.value) {
        params.set('veteran', '1');
    }

    const query = params.toString();

    return query ? `/contact?${query}` : '/contact';
};
</script>

<template>
    <div>
        <!-- ───────── Hero ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white"
        >
            <div
                class="absolute inset-0 -z-10"
                style="
                    background: linear-gradient(
                        180deg,
                        #1e2e44 0%,
                        #243650 55%,
                        #1a2840 100%
                    );
                "
            />
            <img
                src="/american-flag.webp"
                alt=""
                width="1729"
                height="910"
                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-30 mix-blend-luminosity"
                loading="eager"
                fetchpriority="high"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/70 to-[var(--color-navy-deep)]/40"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"
            />

            <div
                class="mx-auto max-w-7xl px-6 pt-36 pb-24 sm:pt-44 sm:pb-32 lg:px-8 lg:pt-52 lg:pb-40"
            >
                <div class="max-w-3xl">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"
                        />
                        Veteran-owned · Built in the USA
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl lg:text-8xl"
                    >
                        Built in America.
                        <span class="block text-[var(--color-red)]"
                            >Not outsourced.</span
                        >
                    </h1>
                    <p
                        class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl"
                    >
                        Custom websites for American small businesses.
                    </p>
                    <p
                        class="mt-5 max-w-xl text-base leading-relaxed text-white/75"
                    >
                        We design and build high-converting websites, by hand,
                        here in the States, with the discipline of a
                        veteran-owned shop. No overseas hand-offs. No
                        cookie-cutter templates.
                    </p>
                    <div
                        class="mt-10 flex flex-col items-start gap-4 sm:flex-row sm:items-center"
                    >
                        <a
                            href="#pricing"
                            class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow-lg shadow-black/30 transition duration-200 ease-out hover:bg-[var(--color-red-deep)] focus-visible:ring-2 focus-visible:ring-[var(--color-red)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-navy-deep)] focus-visible:outline-none"
                        >
                            Start at $299/mo
                            <svg
                                class="ml-2 h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                        <a
                            href="#services"
                            class="inline-flex items-center gap-1 text-sm font-semibold tracking-wide text-white/80 uppercase underline-offset-4 transition hover:text-white hover:underline focus-visible:rounded focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-navy-deep)] focus-visible:outline-none"
                        >
                            See what we do
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                    </div>

                    <dl
                        class="mt-14 grid grid-cols-2 gap-x-8 gap-y-5 border-t border-white/15 pt-8 text-sm"
                        :class="
                            features.businessesLaunched ? 'sm:grid-cols-3' : ''
                        "
                    >
                        <div v-if="features.businessesLaunched">
                            <dt
                                class="text-xs tracking-wider text-white/80 uppercase"
                            >
                                Businesses launched
                            </dt>
                            <dd
                                class="mt-1 flex items-baseline gap-1 font-serif text-2xl text-white"
                            >
                                30<span class="text-base text-white/80">+</span>
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs tracking-wider text-white/80 uppercase"
                            >
                                Average launch
                            </dt>
                            <dd class="mt-1 font-serif text-2xl text-white">
                                1&ndash;2 weeks
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs tracking-wider text-white/80 uppercase"
                            >
                                Where it's built
                            </dt>
                            <dd class="mt-1 font-serif text-2xl text-white">
                                In the USA
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Testimonials ───────── -->
        <section
            v-if="features.testimonials"
            aria-label="What local owners are saying"
            class="bg-[var(--color-cream)] pt-20 sm:pt-24"
        >
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Loved by local owners
                    </p>
                    <h2
                        class="mt-3 font-serif text-3xl tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
                    >
                        Real businesses. Real results.
                    </h2>
                </div>

                <figure
                    class="relative mx-auto mt-12 max-w-3xl overflow-hidden rounded-3xl bg-white p-8 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-12 dark:bg-white/[0.04] dark:ring-white/10"
                >
                    <svg
                        class="absolute -top-4 -left-2 h-24 w-24 text-[var(--color-emerald-600)]/10 sm:h-32 sm:w-32"
                        viewBox="0 0 100 80"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            d="M0 80V40C0 18 18 0 40 0v16C26 16 16 26 16 40h24v40H0zm60 0V40C60 18 78 0 100 0v16C86 16 76 26 76 40h24v40H60z"
                        />
                    </svg>
                    <blockquote
                        class="relative font-serif text-2xl leading-snug text-[var(--color-deep-teal)] italic sm:text-3xl"
                    >
                        “Our new site doubled inquiries in the first month. Jon
                        and Elena actually pick up the phone — and they build it
                        right.”
                    </blockquote>
                    <figcaption class="relative mt-6 flex items-center gap-4">
                        <span
                            class="grid h-12 w-12 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-lg text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                            >SK</span
                        >
                        <div>
                            <p
                                class="font-serif text-base text-[var(--color-deep-teal)]"
                            >
                                Sarah Klein
                            </p>
                            <p class="text-xs text-slate-600">
                                Owner, Sandcastle Vacation Rentals · Destin
                            </p>
                        </div>
                    </figcaption>
                </figure>

                <div class="mx-auto mt-6 grid max-w-5xl gap-6 sm:grid-cols-2">
                    <figure
                        class="rounded-2xl bg-white p-6 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                    >
                        <blockquote
                            class="font-serif text-lg leading-snug text-[var(--color-deep-teal)] italic"
                        >
                            “We rank #1 for the keywords that actually bring in
                            customers. It paid for itself in eight weeks.”
                        </blockquote>
                        <figcaption class="mt-4 flex items-center gap-3">
                            <span
                                class="grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-sm text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >MR</span
                            >
                            <div>
                                <p
                                    class="text-sm font-semibold text-[var(--color-deep-teal)]"
                                >
                                    Marcus Reyes
                                </p>
                                <p class="text-xs text-slate-600">
                                    Reyes HVAC · Panama City Beach
                                </p>
                            </div>
                        </figcaption>
                    </figure>
                    <figure
                        class="rounded-2xl bg-white p-6 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                    >
                        <blockquote
                            class="font-serif text-lg leading-snug text-[var(--color-deep-teal)] italic"
                        >
                            “Three weeks from first call to a launched site I'm
                            proud to send people to. No drama, no contracts.”
                        </blockquote>
                        <figcaption class="mt-4 flex items-center gap-3">
                            <span
                                class="grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-50)] font-serif text-sm text-[var(--color-emerald-700)] ring-1 ring-[var(--color-emerald-600)]/15 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >JT</span
                            >
                            <div>
                                <p
                                    class="text-sm font-semibold text-[var(--color-deep-teal)]"
                                >
                                    Jenna Thibault
                                </p>
                                <p class="text-xs text-slate-600">
                                    30A Coastal Med Spa · Santa Rosa Beach
                                </p>
                            </div>
                        </figcaption>
                    </figure>
                </div>

                <p class="mt-8 text-center text-xs text-slate-500">
                    Why design matters:
                    <a
                        href="https://dl.acm.org/doi/10.1145/997078.997097"
                        target="_blank"
                        rel="noopener"
                        class="underline underline-offset-4 hover:text-[var(--color-emerald-700)]"
                        >46% of people judge a site's credibility on visual
                        design alone</a
                    >
                    (Stanford, Fogg et al.).
                </p>
            </div>
        </section>

        <!-- ───────── Services ───────── -->
        <section id="services" class="bg-[var(--color-cream)] py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        What we do
                    </p>
                    <h2
                        class="mt-3 font-serif text-4xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-5xl"
                    >
                        Web design, SEO, blogs &amp; newsletters.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-600">
                        Everything your small business needs to look
                        professional online and get found by the customers
                        searching for you.
                    </p>
                </div>

                <div class="mx-auto mt-16 grid max-w-6xl gap-6 lg:grid-cols-2">
                    <article
                        v-for="service in foundationServices"
                        :key="service.name"
                        class="group relative flex flex-col overflow-hidden rounded-3xl border border-[var(--color-sand-300)]/60 bg-white p-8 shadow-[0_1px_0_rgba(11,42,46,0.04)] transition duration-200 ease-out hover:-translate-y-0.5 hover:border-[var(--color-emerald-600)]/30 hover:shadow-lg sm:p-10 dark:border-white/10 dark:bg-white/[0.04]"
                    >
                        <span
                            aria-hidden="true"
                            class="pointer-events-none absolute -top-12 -right-12 h-40 w-40 rounded-full bg-[var(--color-navy)]/5 transition duration-300 group-hover:scale-110 dark:bg-white/[0.04]"
                        />
                        <span
                            class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-navy)] text-[var(--color-cream)] ring-1 ring-white/10 dark:bg-white/10 dark:text-white dark:ring-white/15"
                        >
                            <svg
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="h-7 w-7"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    :d="service.icon"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                        <h3
                            class="relative mt-6 font-serif text-2xl text-[var(--color-deep-teal)] sm:text-3xl"
                        >
                            {{ service.name }}
                        </h3>
                        <p
                            class="relative mt-3 text-base leading-relaxed text-slate-600"
                        >
                            {{ service.description }}
                        </p>
                        <p
                            class="relative mt-6 text-xs font-medium tracking-[0.18em] text-[var(--color-red)] uppercase"
                        >
                            Included in every plan
                        </p>
                    </article>
                </div>

                <div class="mx-auto mt-6 max-w-6xl">
                    <div
                        class="rounded-3xl border border-dashed border-[var(--color-red)]/30 bg-[var(--color-navy)]/[0.04] p-6 sm:p-8 dark:border-white/10 dark:bg-white/[0.03]"
                    >
                        <div
                            class="flex flex-col gap-2 sm:flex-row sm:items-baseline sm:justify-between"
                        >
                            <p
                                class="font-serif text-lg text-[var(--color-deep-teal)]"
                            >
                                Also included with
                                <span class="text-[var(--color-red)]"
                                    >Growth</span
                                >
                            </p>
                            <a
                                href="#pricing"
                                class="inline-flex items-center gap-1 text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase hover:text-[var(--color-emerald-800)] focus-visible:rounded focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50 focus-visible:outline-none"
                            >
                                See Growth plan
                                <svg
                                    class="h-3 w-3"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <article
                                v-for="service in growthServices"
                                :key="service.name"
                                class="flex items-start gap-4 rounded-2xl bg-white p-5 ring-1 ring-[var(--color-sand-300)]/60 transition duration-200 ease-out hover:-translate-y-0.5 hover:ring-[var(--color-emerald-600)]/30 dark:bg-white/[0.04] dark:ring-white/10"
                            >
                                <span
                                    class="flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-[var(--color-navy)] text-[var(--color-cream)] ring-1 ring-white/10 dark:bg-white/10 dark:text-white dark:ring-white/15"
                                >
                                    <svg
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="h-4 w-4"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            :d="service.icon"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <div>
                                    <h3
                                        class="font-serif text-lg text-[var(--color-deep-teal)]"
                                    >
                                        {{ service.name }}
                                    </h3>
                                    <p
                                        class="mt-1 text-sm leading-relaxed text-slate-600"
                                    >
                                        {{ service.description }}
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───────── Process ───────── -->
        <section id="process" class="bg-[var(--color-sand-100)] py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        How it works
                    </p>
                    <h2
                        class="mt-3 font-serif text-4xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-5xl"
                    >
                        Our process. Four steps, no surprises.
                    </h2>
                </div>

                <div class="relative mx-auto mt-16 max-w-6xl">
                    <span
                        aria-hidden="true"
                        class="absolute top-5 left-5 hidden h-px w-[calc(100%-2.5rem)] bg-gradient-to-r from-[var(--color-emerald-600)]/30 via-[var(--color-emerald-600)]/30 to-transparent lg:block"
                    />
                    <span
                        aria-hidden="true"
                        class="absolute top-0 bottom-0 left-5 w-px bg-[var(--color-emerald-600)]/20 lg:hidden"
                    />

                    <ol class="lg:grid lg:grid-cols-4 lg:gap-8">
                        <li
                            v-for="[num, title, duration, copy] in processSteps"
                            :key="num"
                            class="relative flex gap-5 pb-10 last:pb-0 lg:flex-col lg:gap-0 lg:pb-0"
                        >
                            <span
                                class="relative z-10 grid h-10 w-10 flex-none place-items-center rounded-full bg-[var(--color-emerald-600)] font-serif text-sm font-semibold text-white shadow-md ring-4 ring-[var(--color-sand-100)]"
                            >
                                {{ num }}
                            </span>
                            <div class="lg:mt-6">
                                <p
                                    class="text-xs font-medium tracking-[0.18em] text-[var(--color-red)] uppercase"
                                >
                                    {{ duration }}
                                </p>
                                <h3
                                    class="mt-1 font-serif text-xl text-[var(--color-deep-teal)] sm:text-2xl"
                                >
                                    {{ title }}
                                </h3>
                                <p
                                    class="mt-2 text-sm leading-relaxed text-slate-600"
                                >
                                    {{ copy }}
                                </p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- ───────── Custom-built ───────── -->
        <section
            id="how-we-build"
            class="bg-[var(--color-cream)] py-24 sm:py-32"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-start gap-12 lg:grid-cols-12">
                    <div class="lg:col-span-5">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                        >
                            How we build
                        </p>
                        <h2
                            class="mt-3 font-serif text-4xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-5xl"
                        >
                            Custom-built with Laravel,
                            <span class="text-[var(--color-red)]">not</span>
                            WordPress, Wix, or Squarespace.
                        </h2>
                        <p class="mt-5 text-lg leading-relaxed text-slate-700">
                            Every site we ship is a real web application,
                            written from scratch in Laravel and engineered to
                            grow with your business. That means we can do far
                            more than a marketing site.
                        </p>
                        <p class="mt-4 text-lg leading-relaxed text-slate-700">
                            Need a booking system that talks to your calendar? A
                            CRM your team will actually use? A customer portal,
                            intake form, or internal tool? We build it, on the
                            same foundation as your website.
                        </p>
                        <p class="mt-8">
                            <Link
                                href="/contact"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)]"
                            >
                                Talk through a custom project
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </p>
                    </div>

                    <div class="lg:col-span-7">
                        <div
                            class="rounded-3xl bg-white p-8 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-10 dark:bg-white/[0.04] dark:ring-white/10"
                        >
                            <p
                                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                            >
                                What we can build
                            </p>
                            <ul
                                class="mt-6 grid gap-x-6 gap-y-4 text-sm text-slate-700 sm:grid-cols-2"
                            >
                                <li
                                    v-for="capability in buildCapabilities"
                                    :key="capability"
                                    class="flex gap-3"
                                >
                                    <CheckIcon color="emerald" />{{
                                        capability
                                    }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───────── Recent work ───────── -->
        <section id="work" class="bg-[var(--color-sand-100)] py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Recent work
                    </p>
                    <h2
                        class="mt-3 font-serif text-4xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-5xl"
                    >
                        Real products we've shipped.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-slate-600">
                        A peek at what we build when the brief goes beyond a
                        marketing site. Full web applications, custom-built from
                        the ground up.
                    </p>
                </div>

                <div class="mx-auto mt-16 grid max-w-6xl gap-8 lg:grid-cols-2">
                    <article
                        v-for="project in projects"
                        :key="project.name"
                        class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 transition duration-200 ease-out hover:-translate-y-1 hover:shadow-xl dark:bg-white/[0.04] dark:ring-white/10"
                    >
                        <div
                            class="border-b border-[var(--color-sand-300)]/60 bg-[var(--color-cream)] px-4 py-3 dark:border-white/10 dark:bg-white/[0.03]"
                        >
                            <div class="flex items-center gap-2">
                                <span
                                    class="h-2.5 w-2.5 rounded-full bg-[#FF5F57]"
                                />
                                <span
                                    class="h-2.5 w-2.5 rounded-full bg-[#FEBC2E]"
                                />
                                <span
                                    class="h-2.5 w-2.5 rounded-full bg-[#28C840]"
                                />
                                <span
                                    class="ml-3 truncate rounded-md bg-white px-3 py-1 text-xs text-slate-500 ring-1 ring-[var(--color-sand-300)]/70 dark:bg-white/10 dark:text-white/70 dark:ring-white/10"
                                >
                                    {{ project.host }}
                                </span>
                            </div>
                        </div>

                        <a
                            :href="project.url"
                            target="_blank"
                            rel="noopener"
                            class="relative block aspect-[16/10] overflow-hidden bg-gradient-to-br from-[var(--color-navy)]/10 to-[var(--color-sand-200)] dark:from-white/[0.04] dark:to-white/[0.02]"
                        >
                            <img
                                :src="project.image"
                                :srcset="project.srcset"
                                sizes="(min-width: 1024px) 560px, 100vw"
                                :width="project.width"
                                :height="project.height"
                                :alt="`Screenshot of ${project.name} — ${project.tagline}`"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover object-top transition duration-300 group-hover:scale-[1.02]"
                            />
                        </a>

                        <div class="flex flex-1 flex-col gap-4 p-8">
                            <div>
                                <p
                                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                                >
                                    {{ project.tagline }}
                                </p>
                                <h3
                                    class="mt-2 font-serif text-2xl text-[var(--color-deep-teal)] sm:text-3xl"
                                >
                                    {{ project.name }}
                                </h3>
                            </div>
                            <p class="text-base leading-relaxed text-slate-600">
                                {{ project.description }}
                            </p>

                            <ul class="flex flex-wrap gap-2 text-xs">
                                <li
                                    v-for="tag in project.tags"
                                    :key="tag"
                                    class="rounded-full bg-[var(--color-navy)]/[0.06] px-2.5 py-1 font-medium text-[var(--color-navy)] ring-1 ring-[var(--color-navy)]/15 dark:bg-white/10 dark:text-white dark:ring-white/15"
                                >
                                    {{ tag }}
                                </li>
                            </ul>

                            <div class="mt-auto pt-2">
                                <a
                                    :href="project.url"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)] focus-visible:rounded focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50 focus-visible:outline-none"
                                >
                                    Visit {{ project.name }}
                                    <svg
                                        class="h-3.5 w-3.5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"
                                        />
                                        <path
                                            d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ───────── About teaser ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-12">
                    <div class="lg:col-span-5">
                        <figure
                            class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                        >
                            <img
                                src="/team/jon-elena.webp"
                                alt="Jon and Elena Ringeisen, co-founders of All American Web Design"
                                width="768"
                                height="1024"
                                loading="lazy"
                                class="block h-auto w-full"
                            />
                        </figure>
                    </div>
                    <div class="lg:col-span-7">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                        >
                            Meet the team
                        </p>
                        <h2
                            class="mt-3 font-serif text-3xl leading-tight font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                        >
                            Family &amp; veteran owned,
                            <span class="text-[var(--color-red)] normal-case"
                                >built in America</span
                            >.
                        </h2>
                        <p class="mt-5 text-lg leading-relaxed text-slate-700">
                            All American Web Design is Jon and Elena, a
                            husband-and-wife team. Jon, a US Army veteran,
                            writes the code; Elena runs the strategy. When you
                            hire us, you get the two people who actually do the
                            work, and a website built by hand, here in the
                            States.
                        </p>
                        <ul class="mt-6 flex flex-wrap gap-2 text-sm">
                            <li
                                class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                            >
                                Husband &amp; wife team
                            </li>
                            <li
                                class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                            >
                                US Army veteran
                            </li>
                            <li
                                class="rounded-full bg-white px-3 py-1.5 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                            >
                                15 yrs engineering
                            </li>
                        </ul>
                        <p class="mt-8">
                            <Link
                                href="/about"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-emerald-700)] hover:text-[var(--color-emerald-800)]"
                            >
                                Read our story
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───────── Made in America ───────── -->
        <section
            id="where-we-work"
            class="relative overflow-hidden bg-[var(--color-sand-100)] py-20 sm:py-24"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-12">
                    <div class="lg:col-span-6">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                        >
                            Made in America
                        </p>
                        <h2
                            class="mt-3 font-serif text-3xl leading-tight font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                        >
                            Built here. For businesses everywhere.
                        </h2>
                        <p
                            class="mt-5 text-base leading-relaxed text-slate-600"
                        >
                            Every site is designed and coded in the United
                            States. No overseas outsourcing, no offshore
                            template farms. We work with small business owners
                            from coast to coast, and we treat every build like
                            it's our own name on the door.
                        </p>
                        <dl class="mt-6 grid grid-cols-2 gap-6">
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-slate-500 uppercase"
                                >
                                    Where we build
                                </dt>
                                <dd
                                    class="mt-1 font-serif text-3xl text-[var(--color-deep-teal)]"
                                >
                                    100% USA
                                </dd>
                            </div>
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-slate-500 uppercase"
                                >
                                    Owners served
                                </dt>
                                <dd
                                    class="mt-1 font-serif text-3xl text-[var(--color-deep-teal)]"
                                >
                                    Nationwide
                                </dd>
                            </div>
                        </dl>
                        <Link
                            href="/contact"
                            class="mt-6 inline-flex items-center text-sm font-semibold tracking-wide text-[var(--color-emerald-700)] uppercase hover:text-[var(--color-emerald-800)]"
                        >
                            Start your project
                            <svg
                                class="ml-1 h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </Link>
                    </div>

                    <div class="lg:col-span-6">
                        <div
                            class="relative isolate overflow-hidden rounded-3xl bg-[var(--color-navy)] p-10 text-white shadow-xl ring-1 ring-black/5 sm:p-12"
                        >
                            <img
                                src="/american-flag.webp"
                                alt=""
                                width="1729"
                                height="910"
                                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-25 mix-blend-luminosity"
                                loading="lazy"
                            />
                            <div
                                class="absolute inset-0 -z-10 bg-gradient-to-br from-[var(--color-navy)]/80 via-[var(--color-navy)]/65 to-[var(--color-navy-deep)]/85"
                            />
                            <p
                                class="relative font-serif text-xs tracking-[0.22em] text-white/70 uppercase"
                            >
                                Est. in the USA
                            </p>
                            <p
                                class="relative mt-3 font-serif text-4xl leading-[0.95] font-bold uppercase sm:text-5xl"
                            >
                                All American<br />Web Design
                            </p>
                            <p
                                class="relative mt-5 max-w-sm text-sm leading-relaxed text-white/75"
                            >
                                Veteran-owned. American-made. Built to last,
                                like the businesses we build for.
                            </p>
                            <div
                                class="relative mt-6 h-1 w-16 bg-[var(--color-red)]"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───────── Pricing ───────── -->
        <section
            id="pricing"
            class="relative bg-[var(--color-deep-teal)] py-24 text-white sm:py-32"
        >
            <div
                class="absolute inset-0 -z-10 opacity-30"
                style="
                    background:
                        radial-gradient(
                            60% 60% at 80% 0%,
                            rgba(59, 83, 120, 0.45),
                            transparent 60%
                        ),
                        radial-gradient(
                            50% 50% at 10% 100%,
                            rgba(180, 86, 75, 0.18),
                            transparent 60%
                        );
                "
            />

            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-200)] uppercase"
                    >
                        Pricing
                    </p>
                    <h2
                        class="mt-3 font-serif text-4xl font-bold tracking-tight uppercase sm:text-5xl"
                    >
                        Pricing that fits how you work.
                    </h2>
                    <p
                        class="mt-3 font-body text-2xl text-[var(--color-sand-200)]/90 italic"
                    >
                        Monthly plans or a one-time build, no contracts.
                    </p>
                    <p class="mt-5 text-lg leading-relaxed text-white/75">
                        Pick the plan that fits. Cancel anytime. Your site is
                        always yours.
                    </p>
                </div>

                <!-- Veteran discount toggle -->
                <div
                    class="mx-auto mt-10 flex max-w-md flex-col items-center gap-3 text-center"
                >
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="veteran"
                        aria-label="Show veteran pricing (20% off)"
                        class="group inline-flex items-center gap-3 rounded-full bg-white/[0.06] px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:outline-none"
                        @click="veteran = !veteran"
                    >
                        <span
                            class="inline-flex h-6 w-11 flex-none items-center rounded-full p-0.5 transition-colors duration-200"
                            :class="
                                veteran
                                    ? 'bg-[var(--color-red)]'
                                    : 'bg-white/20'
                            "
                        >
                            <span
                                class="h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                :class="veteran ? 'translate-x-5' : ''"
                            />
                        </span>
                        Veteran pricing
                        <span
                            class="rounded-full bg-[var(--color-red)] px-2 py-0.5 text-xs font-bold tracking-wide text-white uppercase"
                            >20% off</span
                        >
                    </button>
                    <p
                        v-show="veteran"
                        class="text-sm text-[var(--color-emerald-200)]"
                    >
                        Veteran discount applied. Thank you for your service. 🇺🇸
                    </p>
                </div>

                <div
                    class="mx-auto mt-16 grid max-w-6xl gap-6 sm:mt-20 lg:grid-cols-3"
                >
                    <!-- Essential -->
                    <article
                        class="flex flex-col rounded-3xl bg-white/[0.04] p-8 ring-1 ring-white/15 backdrop-blur"
                    >
                        <div class="flex items-baseline justify-between">
                            <h3 class="font-serif text-2xl">Essential</h3>
                            <span
                                class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80"
                                >Get found</span
                            >
                        </div>
                        <p class="mt-2 text-sm text-white/70">
                            Get a beautiful website and start ranking locally.
                        </p>
                        <p class="mt-6">
                            <span v-if="!veteran">
                                <span class="font-serif text-5xl">$299</span>
                                <span class="ml-1 text-sm text-white/70"
                                    >/month</span
                                >
                            </span>
                            <span v-else>
                                <span class="font-serif text-5xl">$239</span>
                                <span class="ml-1 text-sm text-white/70"
                                    >/month</span
                                >
                                <span
                                    class="ml-2 align-middle text-2xl text-white/50 line-through"
                                    >$299</span
                                >
                            </span>
                        </p>
                        <ul class="mt-8 space-y-3 text-sm text-white/85">
                            <li class="flex gap-3">
                                <CheckIcon />Custom web design
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Mobile-optimized & fast
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />On-page SEO setup
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Local listings (Google, Bing,
                                Apple)
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Google Search Console setup
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Monthly SEO optimization
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Hosting, SSL, backups
                            </li>
                        </ul>
                        <a
                            :href="contactHref('essential')"
                            class="mt-10 inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase transition duration-200 ease-out hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-deep-teal)] focus-visible:outline-none"
                        >
                            Start with Essential
                        </a>
                    </article>

                    <!-- Growth (popular) -->
                    <article
                        class="keep-light relative z-10 flex flex-col rounded-3xl bg-white p-8 text-[var(--color-deep-teal)] shadow-2xl ring-1 shadow-black/40 ring-white/30 lg:-my-6 lg:scale-105 lg:p-10"
                    >
                        <span
                            class="absolute -top-4 left-1/2 inline-flex -translate-x-1/2 items-center gap-1.5 rounded-full bg-[var(--color-emerald-600)] px-4 py-1.5 text-xs font-semibold tracking-[0.14em] text-white uppercase shadow-lg ring-2 shadow-black/30 ring-white"
                        >
                            <svg
                                class="h-3 w-3"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.96a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.364 1.118l1.287 3.96c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.366 2.446c-.785.57-1.84-.197-1.54-1.118l1.287-3.96a1 1 0 00-.364-1.118L2.65 9.247c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.96z"
                                />
                            </svg>
                            Most popular
                        </span>
                        <div class="flex items-baseline justify-between">
                            <h3 class="font-serif text-2xl lg:text-3xl">
                                Growth
                            </h3>
                            <span
                                class="rounded-full bg-[var(--color-navy)]/[0.06] px-3 py-1 text-xs font-medium text-[var(--color-navy)]"
                                >Get found & stay top of mind</span
                            >
                        </div>
                        <p class="mt-2 text-sm text-slate-600">
                            Everything in Essential, plus the content that
                            drives long-term growth.
                        </p>
                        <p class="mt-6">
                            <span v-if="!veteran">
                                <span class="font-serif text-5xl lg:text-6xl"
                                    >$499</span
                                >
                                <span class="ml-1 text-sm text-slate-500"
                                    >/month</span
                                >
                            </span>
                            <span v-else>
                                <span class="font-serif text-5xl lg:text-6xl"
                                    >$399</span
                                >
                                <span class="ml-1 text-sm text-slate-500"
                                    >/month</span
                                >
                                <span
                                    class="ml-2 align-middle text-2xl text-slate-400 line-through"
                                    >$499</span
                                >
                            </span>
                        </p>
                        <ul class="mt-8 space-y-3 text-sm text-slate-700">
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />Everything in
                                Essential
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />2 original blog
                                posts / month
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />Weekly customer
                                newsletter
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />Google Analytics
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />Priority support
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon color="emerald" />Quarterly
                                performance report
                            </li>
                        </ul>
                        <a
                            :href="contactHref('growth')"
                            class="mt-10 inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow transition duration-200 ease-out hover:bg-[var(--color-red-deep)] focus-visible:ring-2 focus-visible:ring-[var(--color-red)] focus-visible:ring-offset-2 focus-visible:ring-offset-white focus-visible:outline-none"
                        >
                            Start with Growth
                        </a>
                    </article>

                    <!-- Build & Own -->
                    <article
                        class="flex flex-col rounded-3xl bg-white/[0.04] p-8 ring-1 ring-white/15 backdrop-blur"
                    >
                        <div class="flex items-baseline justify-between">
                            <h3 class="font-serif text-2xl">Build &amp; Own</h3>
                            <span
                                class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80"
                                >One-time build</span
                            >
                        </div>
                        <p class="mt-2 text-sm text-white/70">
                            For owners who'd rather not be on a managed plan.
                        </p>
                        <p class="mt-6">
                            <span v-if="!veteran">
                                <span class="font-serif text-5xl">$1,000+</span>
                                <span class="ml-1 text-sm text-white/70"
                                    >/one-time</span
                                >
                            </span>
                            <span v-else>
                                <span class="font-serif text-5xl">$800+</span>
                                <span class="ml-1 text-sm text-white/70"
                                    >/one-time</span
                                >
                                <span
                                    class="ml-2 align-middle text-2xl text-white/50 line-through"
                                    >$1,000+</span
                                >
                            </span>
                        </p>
                        <p class="mt-1 text-sm text-white/70">
                            + $20/month hosting, SSL, backups &amp; security
                            updates
                        </p>
                        <ul class="mt-8 space-y-3 text-sm text-white/85">
                            <li class="flex gap-3">
                                <CheckIcon />Everything in Essential
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Google Analytics
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />One-time build, then it's yours
                            </li>
                            <li class="flex gap-3">
                                <CheckIcon />Hosting, SSL, backups &amp;
                                security updates
                            </li>
                        </ul>
                        <a
                            :href="contactHref()"
                            class="mt-10 inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase transition duration-200 ease-out hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-deep-teal)] focus-visible:outline-none"
                        >
                            Contact for pricing
                        </a>
                    </article>
                </div>

                <p class="mt-10 text-center text-sm text-white/60">
                    Need something custom?
                    <Link
                        href="/contact"
                        class="underline underline-offset-4 hover:text-white"
                        >Get in touch</Link
                    >.
                </p>
            </div>
        </section>

        <!-- ───────── FAQ ───────── -->
        <section id="faq" class="bg-[var(--color-cream)] py-24 sm:py-32">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        FAQ
                    </p>
                    <h2
                        class="mt-3 font-serif text-4xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-5xl"
                    >
                        Frequently asked questions.
                    </h2>
                </div>

                <div
                    class="mt-14 divide-y divide-[var(--color-sand-300)]/70 border-y border-[var(--color-sand-300)]/70"
                >
                    <details
                        v-for="faq in faqs"
                        :key="faq.question"
                        class="faq-item group py-6"
                    >
                        <summary
                            class="-mx-2 flex cursor-pointer list-none items-start justify-between gap-6 rounded-xl px-2 py-1 transition duration-200 hover:bg-[var(--color-sand-200)]/30 focus-visible:bg-[var(--color-sand-200)]/40 focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/40 focus-visible:outline-none"
                        >
                            <h3
                                class="font-serif text-lg text-[var(--color-deep-teal)] group-hover:text-[var(--color-emerald-800)] sm:text-xl"
                            >
                                {{ faq.question }}
                            </h3>
                            <span
                                class="mt-1 grid h-8 w-8 flex-none place-items-center rounded-full bg-[var(--color-navy)]/[0.06] text-[var(--color-navy)] transition duration-300 ease-out group-open:rotate-45 group-open:bg-[var(--color-red)] group-open:text-white"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M10 4a.75.75 0 01.75.75v4.5h4.5a.75.75 0 010 1.5h-4.5v4.5a.75.75 0 01-1.5 0v-4.5h-4.5a.75.75 0 010-1.5h4.5v-4.5A.75.75 0 0110 4z"
                                    />
                                </svg>
                            </span>
                        </summary>
                        <div
                            class="faq-answer mt-3 pr-12 text-base leading-relaxed text-slate-600"
                        >
                            {{ faq.answer }}
                        </div>
                    </details>
                </div>

                <p class="mt-10 text-center text-sm text-slate-600">
                    Still have questions?
                    <Link
                        href="/contact"
                        class="inline-flex items-center gap-1 font-semibold text-[var(--color-emerald-700)] underline-offset-4 hover:text-[var(--color-emerald-800)] hover:underline focus-visible:rounded focus-visible:ring-2 focus-visible:ring-[var(--color-emerald-600)]/50 focus-visible:outline-none"
                    >
                        Talk to us
                        <svg
                            class="h-3.5 w-3.5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </Link>
                </p>
            </div>
        </section>

        <!-- ───────── Final CTA ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] py-24 text-white sm:py-32"
        >
            <div
                class="absolute inset-0 -z-10 opacity-50"
                style="
                    background: radial-gradient(
                        50% 60% at 50% 100%,
                        rgba(59, 83, 120, 0.5),
                        transparent 60%
                    );
                "
            />
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <h2
                    class="font-serif text-4xl font-bold tracking-tight uppercase sm:text-5xl"
                >
                    Ready to build something built to last?
                </h2>
                <p class="mt-5 text-lg text-white/80">
                    Pick a plan and we'll have your new site live in weeks, not
                    months, built right here in America.
                </p>
                <div
                    class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row sm:gap-6"
                >
                    <Link
                        :href="contactHref('growth')"
                        class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg shadow-black/30 transition duration-200 ease-out hover:bg-[var(--color-sand-200)] focus-visible:ring-2 focus-visible:ring-[var(--color-sand-200)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)] focus-visible:outline-none"
                    >
                        Start with Growth — $499/mo
                    </Link>
                    <Link
                        href="/contact"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-white/80 underline-offset-4 transition hover:text-white hover:underline focus-visible:rounded focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--color-emerald-900)] focus-visible:outline-none"
                    >
                        Not sure yet? Book a free 15-min call
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
