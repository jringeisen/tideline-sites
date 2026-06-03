<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage();
const companyName = computed<string>(
    () =>
        (page.props.company as { name?: string } | undefined)?.name ??
        'All American Web Design',
);

const open = ref(false);

const navLinks = [
    { label: 'Services', href: '/#services' },
    { label: 'Pricing', href: '/#pricing' },
    { label: 'Service Area', href: '/service-area' },
    { label: 'About', href: '/about' },
    { label: 'Blog', href: '/blog' },
    { label: 'Contact', href: '/contact' },
];

function setOpen(value: boolean): void {
    open.value = value;
    document.body.style.overflow = value ? 'hidden' : '';
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        setOpen(false);
    }
}

onMounted(() => document.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <header class="absolute inset-x-0 top-0 z-30">
        <div
            class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8"
        >
            <Link
                href="/"
                class="flex items-center text-white"
                :aria-label="`${companyName} home`"
            >
                <img
                    src="/logo-dark.webp"
                    :alt="companyName"
                    width="800"
                    height="149"
                    class="h-10 w-auto"
                />
            </Link>

            <nav
                class="hidden items-center gap-9 text-sm font-medium text-white/85 lg:flex"
                aria-label="Primary"
            >
                <Link
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="transition hover:text-white"
                >
                    {{ link.label }}
                </Link>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <Link
                    href="/contact"
                    class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-[var(--color-emerald-900)] shadow-sm transition hover:bg-[var(--color-sand-200)]"
                >
                    Get started
                </Link>
            </div>

            <button
                type="button"
                :aria-expanded="open"
                aria-controls="mobile-menu"
                :aria-label="open ? 'Close menu' : 'Open menu'"
                class="inline-flex items-center justify-center rounded-full p-2 text-white ring-1 ring-white/30 transition hover:bg-white/10 lg:hidden"
                @click="setOpen(!open)"
            >
                <svg
                    v-if="!open"
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="4" y1="12" x2="20" y2="12" />
                    <line x1="4" y1="17" x2="20" y2="17" />
                </svg>
                <svg
                    v-else
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <line x1="6" y1="6" x2="18" y2="18" />
                    <line x1="6" y1="18" x2="18" y2="6" />
                </svg>
            </button>
        </div>

        <div v-show="open" id="mobile-menu" class="lg:hidden">
            <div
                class="fixed inset-0 z-40 bg-[var(--color-navy-deep,#1e2e44)]/70 backdrop-blur-sm"
                @click="setOpen(false)"
            />
            <div
                class="fixed inset-x-0 top-0 z-50 mx-4 mt-4 rounded-3xl bg-[var(--color-emerald-900)] p-6 text-white shadow-2xl ring-1 ring-white/15"
            >
                <div class="flex items-center justify-between">
                    <Link
                        href="/"
                        class="flex items-center text-white"
                        :aria-label="`${companyName} home`"
                    >
                        <img
                            src="/logo-dark.webp"
                            :alt="companyName"
                            width="800"
                            height="149"
                            class="h-10 w-auto"
                        />
                    </Link>
                    <button
                        type="button"
                        aria-label="Close menu"
                        class="inline-flex items-center justify-center rounded-full p-2 text-white ring-1 ring-white/30 transition hover:bg-white/10"
                        @click="setOpen(false)"
                    >
                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <line x1="6" y1="6" x2="18" y2="18" />
                            <line x1="6" y1="18" x2="18" y2="6" />
                        </svg>
                    </button>
                </div>

                <nav
                    class="mt-8 flex flex-col gap-1 text-base font-medium"
                    aria-label="Mobile"
                >
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white"
                        @click="setOpen(false)"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <div
                    class="mt-6 flex flex-col gap-3 border-t border-white/15 pt-6"
                >
                    <Link
                        href="/contact"
                        class="rounded-full bg-white px-4 py-2.5 text-center text-sm font-semibold text-[var(--color-emerald-900)] shadow-sm transition hover:bg-[var(--color-sand-200)]"
                    >
                        Get started
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
