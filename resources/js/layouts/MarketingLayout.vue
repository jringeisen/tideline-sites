<script setup lang="ts">
import { onBeforeUnmount, onMounted } from 'vue';
import MarketingFooter from '@/components/marketing/MarketingFooter.vue';
import MarketingHead from '@/components/marketing/MarketingHead.vue';
import MarketingHeader from '@/components/marketing/MarketingHeader.vue';
import VeteranBanner from '@/components/marketing/VeteranBanner.vue';

// Enable smooth in-page scrolling for anchor links (#pricing, #services, …)
// only while a marketing page is mounted, so it doesn't animate Inertia's
// scroll-to-top reset elsewhere in the app. Skipped when the visitor prefers
// reduced motion.
const SMOOTH_CLASS = 'scroll-smooth';

onMounted(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    document.documentElement.classList.add(SMOOTH_CLASS);
});

onBeforeUnmount(() => {
    document.documentElement.classList.remove(SMOOTH_CLASS);
});
</script>

<template>
    <div class="font-body bg-[var(--color-cream)] text-[var(--color-ink)] antialiased">
        <MarketingHead />

        <a
            href="#main"
            class="sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:not-sr-only focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg"
        >
            Skip to content
        </a>

        <VeteranBanner />

        <div class="relative">
            <MarketingHeader />

            <main id="main">
                <slot />
            </main>
        </div>

        <MarketingFooter />
    </div>
</template>
