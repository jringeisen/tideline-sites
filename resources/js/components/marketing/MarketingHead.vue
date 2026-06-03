<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type MarketingMeta = {
    title?: string;
    description?: string;
    canonical?: string;
    ogImage?: string;
    ogType?: string;
};

const DEFAULT_META: Required<Omit<MarketingMeta, 'canonical'>> = {
    title: 'All American Web Design — Custom Websites, Built in America',
    description:
        'All American Web Design is a veteran-owned studio building custom, high-converting websites for American small businesses — built in America, not outsourced.',
    ogImage: '/og-image.png',
    ogType: 'website',
};

const page = usePage();

const meta = computed<Required<MarketingMeta>>(() => {
    const provided = (page.props.meta ?? {}) as MarketingMeta;

    return {
        title: provided.title ?? DEFAULT_META.title,
        description: provided.description ?? DEFAULT_META.description,
        canonical: provided.canonical ?? page.url,
        ogImage: provided.ogImage ?? DEFAULT_META.ogImage,
        ogType: provided.ogType ?? DEFAULT_META.ogType,
    };
});

const companyName = computed<string>(
    () =>
        (page.props.company as { name?: string } | undefined)?.name ??
        'All American Web Design',
);

// JSON-LD schema blocks supplied by the page's controller.
const schema = computed<Array<Record<string, unknown>>>(
    () => (page.props.schema as Array<Record<string, unknown>>) ?? [],
);

const jsonLd = (block: Record<string, unknown>): string => JSON.stringify(block);
</script>

<template>
    <Head :title="meta.title">
        <meta head-key="description" name="description" :content="meta.description" />
        <link head-key="canonical" rel="canonical" :href="meta.canonical" />

        <meta head-key="og:type" property="og:type" :content="meta.ogType" />
        <meta head-key="og:site_name" property="og:site_name" :content="companyName" />
        <meta head-key="og:title" property="og:title" :content="meta.title" />
        <meta head-key="og:description" property="og:description" :content="meta.description" />
        <meta head-key="og:url" property="og:url" :content="meta.canonical" />
        <meta head-key="og:image" property="og:image" :content="meta.ogImage" />

        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta head-key="twitter:title" name="twitter:title" :content="meta.title" />
        <meta head-key="twitter:description" name="twitter:description" :content="meta.description" />
        <meta head-key="twitter:image" name="twitter:image" :content="meta.ogImage" />

        <!-- eslint-disable vue/no-v-text-v-html-on-component -->
        <component
            :is="'script'"
            v-for="(block, index) in schema"
            :key="index"
            :head-key="`ld-${index}`"
            type="application/ld+json"
            v-html="jsonLd(block)"
        />
        <!-- eslint-enable vue/no-v-text-v-html-on-component -->
    </Head>
</template>
