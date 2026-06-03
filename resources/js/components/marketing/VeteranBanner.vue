<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const STORAGE_KEY = 'aawd:veteran-banner-dismissed';
const dismissed = ref(true);

onMounted(() => {
    try {
        dismissed.value = localStorage.getItem(STORAGE_KEY) === '1';
    } catch {
        dismissed.value = false;
    }
});

function dismiss(): void {
    dismissed.value = true;

    try {
        localStorage.setItem(STORAGE_KEY, '1');
    } catch {
        /* ignore */
    }
}
</script>

<template>
    <div
        v-show="!dismissed"
        class="relative z-40 bg-[var(--color-red)] text-white"
    >
        <div
            class="mx-auto flex max-w-7xl items-center justify-center gap-x-3 gap-y-1 px-10 py-2.5 text-center text-sm sm:px-6 lg:px-8"
        >
            <p class="font-medium">
                <span class="font-semibold"
                    >Veterans save 20% on every plan.</span
                >
                <span class="hidden sm:inline"
                    >Thank you for your service.</span
                >
                <Link
                    href="/?veteran=1#pricing"
                    class="ml-1 inline-flex items-center gap-1 font-semibold underline underline-offset-4 transition hover:text-white/80"
                >
                    See pricing
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
        <button
            type="button"
            aria-label="Dismiss veteran discount banner"
            class="absolute top-1/2 right-2 inline-flex -translate-y-1/2 items-center justify-center rounded-full p-1.5 text-white/80 transition hover:bg-white/15 hover:text-white focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:outline-none"
            @click="dismiss"
        >
            <svg
                class="h-4 w-4"
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
</template>
