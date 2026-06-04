<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Service = {
    slug: string;
    name: string;
    description: string;
    icon: string;
    tagline: string;
};

defineProps<{
    services: Service[];
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
                        What we do
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl"
                    >
                        Services built to
                        <span class="block text-[var(--color-red)]"
                            >grow your business.</span
                        >
                    </h1>
                    <p
                        class="mt-6 max-w-xl text-lg leading-relaxed text-white/80"
                    >
                        Web design, local SEO, blog writing, and newsletters for
                        Gulf Coast small businesses — veteran-owned, built in
                        America, never outsourced.
                    </p>
                </div>
            </div>
            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Services grid ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-2">
                    <Link
                        v-for="service in services"
                        :key="service.slug"
                        :href="`/services/${service.slug}`"
                        class="group flex flex-col rounded-2xl bg-white p-8 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-emerald-700)]/40"
                    >
                        <svg
                            class="h-9 w-9 text-[var(--color-emerald-700)]"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path :d="service.icon" />
                        </svg>
                        <h2
                            class="mt-5 font-serif text-2xl text-[var(--color-deep-teal)]"
                        >
                            {{ service.name }}
                        </h2>
                        <p
                            class="mt-1 text-sm font-semibold tracking-wide text-[var(--color-red)] uppercase"
                        >
                            {{ service.tagline }}
                        </p>
                        <p class="mt-3 leading-relaxed text-slate-600">
                            {{ service.description }}
                        </p>
                        <span
                            class="mt-5 inline-flex items-center text-sm font-semibold text-[var(--color-emerald-700)] transition group-hover:translate-x-0.5"
                        >
                            Learn more →
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
                    Not sure which service you need?
                </h2>
                <p class="mt-4 text-lg text-white/80">
                    Tell us about your business and we'll point you in the right
                    direction — no pressure, no jargon.
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
