<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import { Button } from '@/components/ui/button';
import categoriesRoutes from '@/routes/admin/categories';

type Category = { id: number; name: string; slug: string; description: string | null; posts_count: number };

defineProps<{ categories: Category[] }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Categories', href: categoriesRoutes.index().url },
        ],
    },
});

const destroy = (category: Category) => {
    if (!window.confirm(`Delete "${category.name}"?`)) return;
    router.delete(CategoryController.destroy.url({ category: category.id }));
};
</script>

<template>
    <Head title="Categories" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
                Taxonomy
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                Categories
            </h1>
        </div>
        <Button as-child variant="marketing" size="marketing">
            <Link :href="categoriesRoutes.create().url">New category</Link>
        </Button>
    </header>

    <section
        class="mt-8 overflow-hidden rounded-3xl bg-white ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] dark:bg-white/[0.04] dark:ring-white/10"
    >
        <table class="min-w-full divide-y divide-[var(--color-sand-300)]/60 text-sm dark:divide-white/10">
            <thead class="text-left text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                <tr>
                    <th class="px-5 py-4">Name</th>
                    <th class="px-5 py-4">Slug</th>
                    <th class="px-5 py-4">Posts</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-sand-300)]/60 dark:divide-white/10">
                <tr
                    v-for="category in categories"
                    :key="category.id"
                    class="transition hover:bg-[var(--color-sand-100)]/40 dark:hover:bg-white/[0.02]"
                >
                    <td class="px-5 py-4 font-medium text-[var(--color-deep-teal)]">
                        <Link :href="categoriesRoutes.edit(category.id).url" class="hover:underline">{{ category.name }}</Link>
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">{{ category.slug }}</td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">{{ category.posts_count }}</td>
                    <td class="px-5 py-4 text-right">
                        <Link
                            :href="categoriesRoutes.edit(category.id).url"
                            class="text-sm font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                        >Edit</Link>
                        <button
                            type="button"
                            class="ml-4 text-sm font-medium text-destructive underline-offset-4 hover:underline"
                            @click="destroy(category)"
                        >Delete</button>
                    </td>
                </tr>
                <tr v-if="categories.length === 0">
                    <td colspan="4" class="px-5 py-12 text-center text-slate-500 dark:text-white/60">
                        <p class="font-serif text-xl text-[var(--color-deep-teal)]">No categories yet.</p>
                        <p class="mt-1 text-sm">Categories group related posts.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>
