<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type LocationCard = {
    slug: string;
    name: string;
    display_name: string;
    hero_subhead: string;
};

defineProps<{
    locations: LocationCard[];
}>();
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
                <div class="max-w-3xl">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"
                        />
                        Where we work
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl"
                    >
                        Gulf Coast
                        <span class="block text-[var(--color-red)]"
                            >web design &amp; SEO.</span
                        >
                    </h1>
                    <p
                        class="mt-6 max-w-xl text-lg leading-relaxed text-white/80"
                    >
                        Local web design and SEO for small businesses across
                        Florida's Gulf Coast — from Panama City Beach and Destin
                        to the 30A corridor. Pick your town to see how we help.
                    </p>
                </div>
            </div>
            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Locations grid ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="location in locations"
                        :key="location.slug"
                        :href="`/locations/${location.slug}`"
                        class="group flex flex-col rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-emerald-700)]/40"
                    >
                        <h2
                            class="font-serif text-2xl text-[var(--color-deep-teal)]"
                        >
                            {{ location.name }}
                        </h2>
                        <p
                            class="mt-3 flex-1 text-sm leading-relaxed text-slate-600"
                            v-html="location.hero_subhead"
                        />
                        <span
                            class="mt-5 inline-flex items-center text-sm font-semibold text-[var(--color-emerald-700)] transition group-hover:translate-x-0.5"
                        >
                            {{ location.name }} web design →
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ───────── Final CTA ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] py-20 text-white sm:py-28"
        >
            <div class="mx-auto max-w-3xl px-6 text-center lg:px-8">
                <h2
                    class="font-serif text-3xl font-bold tracking-tight uppercase sm:text-4xl"
                >
                    Don't see your town?
                </h2>
                <p class="mt-4 text-lg text-white/80">
                    We work with businesses all along the Gulf Coast and across
                    the country. Reach out and we'll tell you how we can help.
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
