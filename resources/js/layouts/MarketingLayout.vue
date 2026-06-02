<script setup lang="ts">
import MarketingFooter from '@/components/marketing/MarketingFooter.vue';
import MarketingHeader from '@/components/marketing/MarketingHeader.vue';
import VeteranBanner from '@/components/marketing/VeteranBanner.vue';
</script>

<template>
    <div class="font-body bg-[var(--color-cream)] text-[var(--color-ink)] antialiased">
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
