<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type Faq = {
    question: string;
    answer: string;
};

type Location = {
    slug: string;
    name: string;
    display_name: string;
    tagline: string;
    hero_subhead: string;
    meta_description: string;
    intro: string;
    why_local: string;
    segments: Array<[string, string]>;
    neighborhoods?: string[];
    faqs?: Faq[];
    geo: { lat: number; lng: number };
};

type NearbyTown = {
    name: string;
    slug: string | null;
};

const props = defineProps<{
    location: Location;
    nearby: NearbyTown[];
}>();

const mapSrc = computed<string>(
    () =>
        `https://www.google.com/maps?q=${encodeURIComponent(
            props.location.display_name,
        )}&z=11&output=embed`,
);

const services = computed<Array<[string, string, string]>>(() => [
    [
        'Web Design',
        `Fast, beautiful sites built for ${props.location.name} customers.`,
        'web-design',
    ],
    [
        'SEO Optimization',
        `Local SEO that wins searches in ${props.location.name} and nearby towns.`,
        'seo',
    ],
    [
        'Blog Writing',
        'Original posts that pull search traffic into your business.',
        'blog-writing',
    ],
    [
        'Newsletters',
        'Monthly emails that keep past customers coming back.',
        'newsletters',
    ],
]);
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
            <img
                src="/american-flag.webp"
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
                        <li class="text-white/85">{{ location.name }}</li>
                    </ol>
                </nav>

                <div class="max-w-3xl">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"
                        />
                        Serving {{ location.display_name }}
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl lg:text-8xl"
                    >
                        {{ location.name }}
                        <span class="block text-[var(--color-red)]"
                            ><span v-html="location.tagline" />.</span
                        >
                    </h1>
                    <p
                        class="mt-6 max-w-xl text-2xl leading-snug text-white/90 sm:text-3xl"
                        v-html="location.hero_subhead"
                    />
                    <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                        <Link
                            href="/contact"
                            class="inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow-lg shadow-black/30 transition hover:bg-[var(--color-red-deep)]"
                        >
                            Get a quote for your {{ location.name }} business
                        </Link>
                        <Link
                            href="/#pricing"
                            class="inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase transition hover:bg-white/10"
                        >
                            See pricing
                        </Link>
                    </div>
                </div>
            </div>

            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Local context ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    Why {{ location.name }}
                </p>
                <h2
                    class="mt-3 font-serif text-3xl leading-tight font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                >
                    Web design and SEO built for {{ location.name }}.
                </h2>
                <p class="mt-6 text-lg leading-relaxed text-slate-700">
                    {{ location.intro }}
                </p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700">
                    {{ location.why_local }}
                </p>

                <div v-if="location.neighborhoods?.length" class="mt-8">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Areas we cover in {{ location.name }}
                    </p>
                    <ul class="mt-4 flex flex-wrap gap-2 text-sm">
                        <li
                            v-for="area in location.neighborhoods"
                            :key="area"
                            class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15"
                        >
                            {{ area }}
                        </li>
                    </ul>
                </div>

                <div
                    class="mt-10 overflow-hidden rounded-2xl ring-1 ring-[var(--color-sand-300)]/60"
                >
                    <iframe
                        :src="mapSrc"
                        :title="`Map of ${location.display_name}`"
                        class="h-72 w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    />
                </div>
            </div>
        </section>

        <!-- ───────── Segments ───────── -->
        <section class="bg-[var(--color-sand-100)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Who we serve
                    </p>
                    <h2
                        class="mt-3 font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                    >
                        {{ location.name }} businesses we love working with.
                    </h2>
                </div>

                <div class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-2">
                    <article
                        v-for="(segment, index) in location.segments"
                        :key="index"
                        class="rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                    >
                        <h3
                            class="font-serif text-xl text-[var(--color-deep-teal)]"
                            v-html="segment[0]"
                        />
                        <p
                            class="mt-2 text-sm leading-relaxed text-slate-600"
                            v-html="segment[1]"
                        />
                    </article>
                </div>
            </div>
        </section>

        <!-- ───────── Services overview ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        What we do
                    </p>
                    <h2
                        class="mt-3 font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                    >
                        Everything {{ location.name }} businesses need to win
                        online.
                    </h2>
                </div>

                <div
                    class="mx-auto mt-14 grid max-w-5xl gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <Link
                        v-for="(service, index) in services"
                        :key="index"
                        :href="`/services/${service[2]}`"
                        class="group rounded-2xl border border-[var(--color-sand-300)]/60 bg-white p-6 transition hover:border-[var(--color-emerald-600)]/40 dark:border-white/10 dark:bg-white/[0.04]"
                    >
                        <h3
                            class="font-serif text-lg text-[var(--color-deep-teal)]"
                        >
                            {{ service[0] }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ service[1] }}
                        </p>
                        <span
                            class="mt-4 inline-flex items-center text-sm font-semibold text-[var(--color-emerald-700)] transition group-hover:translate-x-0.5"
                        >
                            Learn more →
                        </span>
                    </Link>
                </div>

                <p class="mt-10 text-center text-sm text-slate-600">
                    <Link
                        href="/services"
                        class="font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                        >See all four services →</Link
                    >
                </p>
            </div>
        </section>

        <!-- ───────── FAQ ───────── -->
        <section
            v-if="location.faqs?.length"
            class="bg-[var(--color-cream)] py-20 sm:py-28"
        >
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="text-center">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Questions
                    </p>
                    <h2
                        class="mt-3 font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase sm:text-4xl"
                    >
                        {{ location.name }} web design FAQs
                    </h2>
                </div>
                <dl class="mt-12 space-y-8">
                    <div
                        v-for="(faq, index) in location.faqs"
                        :key="index"
                        class="border-b border-[var(--color-sand-300)]/60 pb-8 last:border-0"
                    >
                        <dt
                            class="font-serif text-lg text-[var(--color-deep-teal)]"
                        >
                            {{ faq.question }}
                        </dt>
                        <dd
                            class="mt-3 text-base leading-relaxed text-slate-700"
                        >
                            {{ faq.answer }}
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ───────── Nearby ───────── -->
        <section v-if="nearby.length" class="bg-[var(--color-sand-100)] py-16">
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    We also serve
                </p>
                <h2
                    class="mt-3 font-serif text-2xl font-bold text-[var(--color-deep-teal)] uppercase"
                >
                    Nearby Gulf Coast towns
                </h2>
                <ul class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
                    <li v-for="town in nearby" :key="town.name">
                        <Link
                            v-if="town.slug"
                            :href="`/locations/${town.slug}`"
                            class="rounded-full bg-white px-3 py-1 text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-emerald-50)] dark:bg-white/[0.06] dark:text-white dark:ring-white/15 dark:hover:bg-white/15"
                        >
                            {{ town.name }}
                        </Link>
                        <span
                            v-else
                            class="rounded-full bg-white/60 px-3 py-1 text-slate-600 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
                        >
                            {{ town.name }}
                        </span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- ───────── Final CTA ───────── -->
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
                    Ready to put {{ location.name }} on the map?
                </h2>
                <p class="mt-4 text-lg text-white/80">
                    Tell us about your business and we'll be in touch within one
                    business day.
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
