<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
    eyebrow?: string;
}>();

const company = computed(() => usePage().props.company);
</script>

<template>
    <div
        class="flex min-h-svh flex-col bg-[var(--color-cream)] text-[var(--color-ink)]"
    >
        <header class="w-full">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8"
            >
                <a
                    :href="home.url()"
                    class="flex items-center gap-2.5 text-[var(--color-deep-teal)]"
                    :aria-label="`${company.name} home`"
                >
                    <img
                        src="/logo-light.png"
                        :alt="company.name"
                        class="h-9 w-auto object-contain"
                    />
                </a>

                <a
                    :href="home.url()"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--color-emerald-700)] transition hover:text-[var(--color-emerald-800)]"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Back to home
                </a>
            </div>
        </header>

        <main
            class="flex flex-1 items-center justify-center px-6 py-12 lg:py-20"
        >
            <div class="w-full max-w-md">
                <div
                    class="rounded-3xl bg-white p-8 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-10 dark:bg-white/[0.04] dark:ring-white/10"
                >
                    <div class="text-center">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                        >
                            {{ eyebrow ?? company.name }}
                        </p>
                        <h1
                            class="mt-3 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
                        >
                            {{ title }}
                        </h1>
                        <p
                            v-if="description"
                            class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-white/70"
                        >
                            {{ description }}
                        </p>
                    </div>

                    <div class="mt-8">
                        <slot />
                    </div>
                </div>
            </div>
        </main>

        <footer class="w-full">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-8 text-xs text-slate-500 sm:flex-row lg:px-8 dark:text-white/50"
            >
                <p>
                    &copy; {{ new Date().getFullYear() }} {{ company.name }}.
                    All rights reserved.
                </p>
                <p>Made on the Emerald Coast.</p>
            </div>
        </footer>
    </div>
</template>
