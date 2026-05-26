<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

export type TaxonomyRow = {
    id: number;
    name: string;
    slug: string;
    posts_count: number;
};

defineProps<{
    items: TaxonomyRow[];
    editUrl: (id: number) => string;
    emptyTitle: string;
    emptyBody: string;
}>();

const emit = defineEmits<{ delete: [item: TaxonomyRow] }>();
</script>

<template>
    <section
        class="mt-8 overflow-hidden rounded-3xl bg-white shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <table
            class="min-w-full divide-y divide-[var(--color-sand-300)]/60 text-sm dark:divide-white/10"
        >
            <thead
                class="text-left text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
            >
                <tr>
                    <th class="px-5 py-4">Name</th>
                    <th class="px-5 py-4">Slug</th>
                    <th class="px-5 py-4">Posts</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody
                class="divide-y divide-[var(--color-sand-300)]/60 dark:divide-white/10"
            >
                <tr
                    v-for="item in items"
                    :key="item.id"
                    class="transition hover:bg-[var(--color-sand-100)]/40 dark:hover:bg-white/[0.02]"
                >
                    <td
                        class="px-5 py-4 font-medium text-[var(--color-deep-teal)]"
                    >
                        <Link
                            :href="editUrl(item.id)"
                            class="hover:underline"
                            >{{ item.name }}</Link
                        >
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ item.slug }}
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ item.posts_count }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <Link
                            :href="editUrl(item.id)"
                            class="text-sm font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                            >Edit</Link
                        >
                        <button
                            type="button"
                            class="ml-4 text-sm font-medium text-destructive underline-offset-4 hover:underline"
                            @click="emit('delete', item)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                <tr v-if="items.length === 0">
                    <td
                        colspan="4"
                        class="px-5 py-12 text-center text-slate-500 dark:text-white/60"
                    >
                        <p
                            class="font-serif text-xl text-[var(--color-deep-teal)]"
                        >
                            {{ emptyTitle }}
                        </p>
                        <p class="mt-1 text-sm">{{ emptyBody }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>
