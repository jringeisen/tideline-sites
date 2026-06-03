<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type PageLink = {
    url: string | null;
    label: string;
    active: boolean;
};

defineProps<{ links: PageLink[] }>();
</script>

<template>
    <nav v-if="links.length > 3" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="inline-flex min-w-9 items-center justify-center rounded-lg px-3 py-2 text-sm text-slate-400"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                preserve-scroll
                class="inline-flex min-w-9 items-center justify-center rounded-lg px-3 py-2 text-sm transition"
                :class="
                    link.active
                        ? 'bg-[var(--color-emerald-900)] text-white'
                        : 'text-[var(--color-deep-teal)] hover:bg-[var(--color-sand-100)] dark:text-white dark:hover:bg-white/[0.08]'
                "
            >
                <span v-html="link.label" />
            </Link>
        </template>
    </nav>
</template>
