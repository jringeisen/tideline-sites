<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Value = {
    title: string;
    copy: string;
    icon: string;
};

const values: Value[] = [
    {
        title: 'Family-owned',
        copy: "A husband-and-wife team that has skin in the game on every project. Your success is our family's livelihood.",
        icon: 'M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 018-2.828A4.5 4.5 0 0118 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 01-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 01-.69.001l-.002-.001z',
    },
    {
        title: 'Veteran-owned',
        copy: "Eight years in the US Army taught Jon that discipline and follow-through aren't optional. We bring the same standard to your website.",
        icon: 'M9.661 2.237a.531.531 0 01.678 0 11.947 11.947 0 007.078 2.749.5.5 0 01.479.425c.069.52.104 1.05.104 1.59 0 5.162-3.26 9.563-7.834 11.256a.48.48 0 01-.332 0C5.26 16.814 2 12.413 2 7.251c0-.54.035-1.07.104-1.59a.5.5 0 01.48-.425 11.947 11.947 0 007.077-2.75z',
    },
    {
        title: 'American-made',
        copy: 'Every site is designed and built right here in the United States. Never outsourced, never offshored. American craftsmanship you can stand behind.',
        icon: 'M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z',
    },
];
</script>

<template>
    <div>
        <!-- ───────── Hero ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white"
        >
            <!-- Base navy field -->
            <div
                class="absolute inset-0 -z-10"
                style="
                    background: linear-gradient(
                        180deg,
                        #1e2e44 0%,
                        #243650 60%,
                        #1a2840 100%
                    );
                "
            />
            <!-- American flag, muted into the navy via luminosity blend -->
            <img
                src="/american-flag.png"
                alt=""
                width="1729"
                height="910"
                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
                loading="eager"
                fetchpriority="high"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"
            />

            <div
                class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52"
            >
                <nav aria-label="Breadcrumb" class="mb-6 text-sm text-white/60">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li>
                            <Link href="/" class="hover:text-white">Home</Link>
                        </li>
                        <li aria-hidden="true">›</li>
                        <li class="text-white/85">About</li>
                    </ol>
                </nav>

                <div class="max-w-3xl">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"
                        />
                        About All American Web Design
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl lg:text-8xl"
                    >
                        Family-owned.
                        <span class="block text-[var(--color-red)]"
                            >Veteran-owned.</span
                        >
                    </h1>
                    <p
                        class="mt-6 max-w-2xl text-2xl leading-snug text-white/90 sm:text-3xl"
                    >
                        A husband-and-wife team building custom websites for
                        American small businesses, by hand, here in the States.
                    </p>
                </div>
            </div>

            <!-- Heritage stripe divider -->
            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Story ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    Our story
                </p>
                <h2
                    class="mt-3 font-serif text-3xl leading-tight font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                >
                    Built by two people who care about the work.
                </h2>
                <div
                    class="mt-6 space-y-4 text-lg leading-relaxed text-slate-700"
                >
                    <p>
                        All American Web Design is a husband-and-wife team. We
                        started it for a simple reason: we'd watched too many
                        small business owners get stuck with cookie-cutter
                        websites, ghosted by their agencies, and locked into
                        contracts that didn't serve them.
                    </p>
                    <p>
                        We do things differently. Jon writes the code. Elena
                        handles the strategy. There's no junior account manager
                        bouncing your emails to a developer overseas. Every site
                        we build is built right here in America, by the two
                        people you actually hired.
                    </p>
                    <p>
                        We're proud to be family-owned and veteran-owned, and
                        proud to build every website in the USA. We're not going
                        anywhere, and neither is your website.
                    </p>
                </div>
            </div>
        </section>

        <!-- ───────── Bios ───────── -->
        <section class="bg-[var(--color-sand-100)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-12">
                    <figure class="lg:col-span-6">
                        <div
                            class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                        >
                            <img
                                src="/team/jon-elena.jpeg"
                                alt="Jon and Elena Ringeisen, co-founders of All American Web Design"
                                width="1200"
                                height="900"
                                loading="lazy"
                                class="block h-auto w-full"
                            />
                        </div>
                        <figcaption
                            class="mt-3 text-center text-xs text-slate-500"
                        >
                            Jon &amp; Elena Ringeisen, co-founders
                        </figcaption>
                    </figure>

                    <div class="space-y-8 lg:col-span-6">
                        <article>
                            <div
                                class="flex flex-wrap items-baseline gap-x-3 gap-y-1"
                            >
                                <h3
                                    class="font-serif text-2xl text-[var(--color-deep-teal)]"
                                >
                                    Jon Ringeisen
                                </h3>
                                <span
                                    class="text-sm font-medium tracking-wider text-[var(--color-red)] uppercase"
                                    >Co-founder &amp; Lead Developer</span
                                >
                            </div>
                            <p
                                class="mt-3 text-base leading-relaxed text-slate-700"
                            >
                                Jon has been a software engineer for 15 years,
                                with a string of SaaS products to his name and
                                one successful exit behind him. Before software,
                                he served eight years in the U.S. Army as a
                                paratrooper and 35F intelligence analyst,
                                finishing his enlistment as a Sergeant with two
                                combat deployments, Mosul, Iraq and Kuwait. At
                                All American Web Design he writes the code, runs
                                the SEO, and sweats the details that decide
                                whether your site loads fast and ranks well.
                            </p>
                            <ul class="mt-4 flex flex-wrap gap-2 text-xs">
                                <li
                                    class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >
                                    U.S. Army Veteran
                                </li>
                                <li
                                    class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >
                                    15 yrs engineering
                                </li>
                            </ul>
                        </article>

                        <article>
                            <div
                                class="flex flex-wrap items-baseline gap-x-3 gap-y-1"
                            >
                                <h3
                                    class="font-serif text-2xl text-[var(--color-deep-teal)]"
                                >
                                    Elena Ringeisen
                                </h3>
                                <span
                                    class="text-sm font-medium tracking-wider text-[var(--color-red)] uppercase"
                                    >Co-founder, Marketing &amp; Sales</span
                                >
                            </div>
                            <p
                                class="mt-3 text-base leading-relaxed text-slate-700"
                            >
                                Elena is an entrepreneur, a homeschooling mom of
                                four, and the marketing and sales mind behind
                                All American Web Design. She knows how to talk
                                to small business owners because she is one, and
                                she's run enough of her own ventures to know
                                what it takes to grow a brand from zero. She
                                handles the conversations, the strategy, and the
                                customer side of the business, and somehow still
                                teaches four kids long division.
                            </p>
                            <ul class="mt-4 flex flex-wrap gap-2 text-xs">
                                <li
                                    class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >
                                    Marketing &amp; Sales
                                </li>
                                <li
                                    class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >
                                    Mom of 4
                                </li>
                                <li
                                    class="rounded-full bg-white px-3 py-1 text-[var(--color-emerald-800)] ring-1 ring-[var(--color-emerald-600)]/20 dark:bg-white/10 dark:text-[var(--color-emerald-200)] dark:ring-white/15"
                                >
                                    Entrepreneur
                                </li>
                            </ul>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───────── Values ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        What we stand for
                    </p>
                    <h2
                        class="mt-3 font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                    >
                        Three things we never compromise on.
                    </h2>
                </div>

                <div class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-3">
                    <article
                        v-for="value in values"
                        :key="value.title"
                        class="rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                    >
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-navy)] text-[var(--color-cream)] ring-1 ring-white/10 dark:bg-white/10 dark:text-white dark:ring-white/15"
                        >
                            <svg
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="h-5 w-5"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    :d="value.icon"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                        <h3
                            class="mt-5 font-serif text-xl text-[var(--color-deep-teal)]"
                        >
                            {{ value.title }}
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            {{ value.copy }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ───────── CTA ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] py-20 text-white sm:py-28"
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
                    class="font-serif text-3xl font-bold tracking-tight uppercase sm:text-4xl"
                >
                    Want to work with us?
                </h2>
                <p class="mt-4 text-lg text-white/80">
                    Tell us about your business. We'll get back to you within
                    one business day, from one real person, not a sales bot.
                </p>
                <div class="mt-8">
                    <Link
                        href="/contact"
                        class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[var(--color-emerald-900)] shadow-lg transition hover:bg-[var(--color-sand-200)]"
                    >
                        Get in touch
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
