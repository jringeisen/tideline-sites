<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type Company = {
    name?: string;
    email?: string;
    phone?: string | null;
    locality?: string;
    region?: string;
};

const page = usePage();
const company = computed<Company>(
    () => (page.props.company as Company | undefined) ?? {},
);
const companyName = computed<string>(
    () => company.value.name ?? 'All American Web Design',
);
const companyEmail = computed<string | undefined>(() => company.value.email);
const companyPhone = computed<string | null>(() => company.value.phone ?? null);
const phoneHref = computed<string>(
    () => `tel:+1${(company.value.phone ?? '').replace(/\D/g, '')}`,
);
const cityState = computed<string>(() => {
    const { locality, region } = company.value;

    return [locality, region].filter(Boolean).join(', ');
});
const year = new Date().getFullYear();

const companyLinks = [
    { label: 'Services', href: '/services' },
    { label: 'Pricing', href: '/#pricing' },
    { label: 'Locations', href: '/locations' },
    { label: 'About', href: '/about' },
    { label: 'Blog', href: '/blog' },
    { label: 'Contact', href: '/contact' },
];

const areas = [
    { label: 'Destin', href: '/locations/destin' },
    { label: '30A', href: '/locations/30a' },
    { label: 'Panama City Beach', href: '/locations/panama-city-beach' },
];
</script>

<template>
    <footer class="bg-[var(--color-emerald-900)] text-white/80">
        <div class="mx-auto max-w-7xl px-6 pt-16 pb-10 lg:px-8">
            <div class="grid gap-10 md:grid-cols-12">
                <div class="md:col-span-5">
                    <Link href="/" class="flex items-center text-white">
                        <img
                            src="/logo-dark.webp"
                            :alt="companyName"
                            width="800"
                            height="149"
                            class="h-14 w-auto"
                        />
                    </Link>
                    <p
                        class="mt-4 max-w-sm text-sm leading-relaxed text-white/70"
                    >
                        Veteran-owned web design for American small businesses.
                        Custom websites built in America, never outsourced,
                        never templated.
                    </p>

                    <address
                        class="mt-6 space-y-1 text-sm text-white/70 not-italic"
                    >
                        <p class="font-semibold text-white/90">
                            {{ companyName }}
                        </p>
                        <p v-if="cityState">{{ cityState }}</p>
                        <p v-if="companyPhone">
                            <a
                                :href="phoneHref"
                                class="transition hover:text-white"
                                >{{ companyPhone }}</a
                            >
                        </p>
                        <p v-if="companyEmail">
                            <a
                                :href="`mailto:${companyEmail}`"
                                class="transition hover:text-white"
                                >{{ companyEmail }}</a
                            >
                        </p>
                    </address>
                </div>

                <div class="md:col-span-3">
                    <h3
                        class="text-xs font-semibold tracking-widest text-white/60 uppercase"
                    >
                        Company
                    </h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li v-for="link in companyLinks" :key="link.href">
                            <Link
                                :href="link.href"
                                class="transition hover:text-white"
                                >{{ link.label }}</Link
                            >
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-4">
                    <h3
                        class="text-xs font-semibold tracking-widest text-white/60 uppercase"
                    >
                        Areas We Serve
                    </h3>
                    <ul class="mt-4 flex flex-wrap gap-2 text-sm">
                        <li v-for="area in areas" :key="area.href">
                            <Link
                                :href="area.href"
                                class="rounded-full bg-white/10 px-3 py-1 text-white/90 transition hover:bg-white/20"
                            >
                                {{ area.label }}
                            </Link>
                        </li>
                    </ul>
                    <p class="mt-4 text-sm leading-relaxed text-white/60">
                        Proudly building websites for small businesses across
                        the United States, with local roots on Florida's Gulf
                        Coast.
                    </p>
                    <Link
                        href="/service-area"
                        class="mt-4 inline-flex items-center text-sm font-semibold text-white/90 transition hover:text-white"
                    >
                        See where we work
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
            </div>

            <div
                class="mt-12 flex flex-col items-start justify-between gap-4 border-t border-white/10 pt-6 text-xs text-white/60 sm:flex-row sm:items-center"
            >
                <p>&copy; {{ year }} {{ companyName }}. All rights reserved.</p>
                <p>Veteran-owned &middot; Built in America.</p>
            </div>
        </div>
    </footer>
</template>
