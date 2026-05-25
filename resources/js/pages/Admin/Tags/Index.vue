<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import TagController from '@/actions/App/Http/Controllers/Admin/TagController';
import { Button } from '@/components/ui/button';
import tagsRoutes from '@/routes/admin/tags';

type Tag = { id: number; name: string; slug: string; posts_count: number };

defineProps<{ tags: Tag[] }>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Tags', href: tagsRoutes.index().url }],
    },
});

const destroy = (tag: Tag) => {
    if (!window.confirm(`Delete "${tag.name}"?`)) {
        return;
    }

    router.delete(TagController.destroy.url({ tag: tag.id }));
};
</script>

<template>
    <Head title="Tags" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p
                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
            >
                Taxonomy
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                Tags
            </h1>
        </div>
        <Button as-child variant="marketing" size="marketing">
            <Link :href="tagsRoutes.create().url">New tag</Link>
        </Button>
    </header>

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
                    v-for="tag in tags"
                    :key="tag.id"
                    class="transition hover:bg-[var(--color-sand-100)]/40 dark:hover:bg-white/[0.02]"
                >
                    <td
                        class="px-5 py-4 font-medium text-[var(--color-deep-teal)]"
                    >
                        <Link
                            :href="tagsRoutes.edit(tag.id).url"
                            class="hover:underline"
                            >{{ tag.name }}</Link
                        >
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ tag.slug }}
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ tag.posts_count }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <Link
                            :href="tagsRoutes.edit(tag.id).url"
                            class="text-sm font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                            >Edit</Link
                        >
                        <button
                            type="button"
                            class="ml-4 text-sm font-medium text-destructive underline-offset-4 hover:underline"
                            @click="destroy(tag)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                <tr v-if="tags.length === 0">
                    <td
                        colspan="4"
                        class="px-5 py-12 text-center text-slate-500 dark:text-white/60"
                    >
                        <p
                            class="font-serif text-xl text-[var(--color-deep-teal)]"
                        >
                            No tags yet.
                        </p>
                        <p class="mt-1 text-sm">
                            Tags help readers explore related posts.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>
