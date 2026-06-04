<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Faq = {
    question: string;
    answer: string;
};

type Service = {
    slug: string;
    name: string;
    description: string;
    icon: string;
    tagline: string;
    intro: string;
    body: string[];
    includes: string[];
    faqs: Faq[];
};

defineProps<{
    service: Service;
}>();

// A few priority Gulf Coast markets to cross-link from each service page.
const markets = [
    { name: 'Panama City Beach', slug: 'panama-city-beach' },
    { name: 'Destin', slug: 'destin' },
    { name: '30A', slug: '30a' },
];
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
                        #243650 60%,
                        #1a2840 100%
                    );
                "
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
                        <li>
                            <Link href="/services" class="hover:text-white"
                                >Services</Link
                            >
                        </li>
                        <li aria-hidden="true">›</li>
                        <li class="text-white/85">{{ service.name }}</li>
                    </ol>
                </nav>

                <div class="max-w-3xl">
                    <svg
                        class="h-10 w-10 text-[var(--color-red)]"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path :d="service.icon" />
                    </svg>
                    <h1
                        class="mt-6 font-serif text-5xl leading-[0.95] font-bold tracking-tight uppercase sm:text-6xl"
                    >
                        {{ service.name }}
                    </h1>
                    <p
                        class="mt-3 text-lg font-semibold tracking-wide text-[var(--color-red)] uppercase"
                    >
                        {{ service.tagline }}
                    </p>
                    <p
                        class="mt-6 max-w-xl text-lg leading-relaxed text-white/85"
                    >
                        {{ service.intro }}
                    </p>
                    <div class="mt-10">
                        <Link
                            href="/contact"
                            class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow-lg shadow-black/30 transition hover:bg-[var(--color-red-deep)]"
                        >
                            Get a quote
                        </Link>
                    </div>
                </div>
            </div>
            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Body + includes ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div
                class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-3 lg:px-8"
            >
                <div class="lg:col-span-2">
                    <p
                        v-for="(paragraph, index) in service.body"
                        :key="index"
                        class="text-lg leading-relaxed text-slate-700"
                        :class="{ 'mt-6': index > 0 }"
                    >
                        {{ paragraph }}
                    </p>
                </div>
                <aside
                    class="rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                >
                    <h2
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        What's included
                    </h2>
                    <ul class="mt-5 space-y-3 text-sm text-slate-700">
                        <li
                            v-for="item in service.includes"
                            :key="item"
                            class="flex gap-3"
                        >
                            <svg
                                class="mt-0.5 h-5 w-5 flex-none text-[var(--color-emerald-700)]"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.704 5.29a1 1 0 010 1.42l-7.5 7.5a1 1 0 01-1.42 0l-3.5-3.5a1 1 0 011.42-1.42l2.79 2.79 6.79-6.79a1 1 0 011.42 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ item }}
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <!-- ───────── FAQ ───────── -->
        <section
            v-if="service.faqs?.length"
            class="bg-[var(--color-sand-100)] py-20 sm:py-28"
        >
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <h2
                    class="text-center font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                >
                    {{ service.name }} FAQs
                </h2>
                <dl class="mt-12 space-y-8">
                    <div
                        v-for="(faq, index) in service.faqs"
                        :key="index"
                        class="border-b border-[var(--color-sand-300)]/60 pb-8 last:border-0"
                    >
                        <dt
                            class="font-serif text-lg text-[var(--color-deep-teal)]"
                        >
                            {{ faq.question }}
                        </dt>
                        <dd class="mt-3 leading-relaxed text-slate-700">
                            {{ faq.answer }}
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ───────── Local cross-links ───────── -->
        <section class="bg-[var(--color-cream)] py-16">
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    {{ service.name }} near you
                </p>
                <h2
                    class="mt-3 font-serif text-2xl font-bold text-[var(--color-deep-teal)] uppercase"
                >
                    Serving Florida's Gulf Coast
                </h2>
                <ul class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
                    <li v-for="market in markets" :key="market.slug">
                        <Link
                            :href="`/locations/${market.slug}`"
                            class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-emerald-50)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15 dark:hover:bg-white/15"
                        >
                            {{ market.name }} {{ service.name }}
                        </Link>
                    </li>
                    <li>
                        <Link
                            href="/locations"
                            class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-emerald-50)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15 dark:hover:bg-white/15"
                        >
                            All locations →
                        </Link>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</template>
