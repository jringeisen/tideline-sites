<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PostController from '@/actions/App/Http/Controllers/Admin/PostController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import postsRoutes from '@/routes/admin/posts';

type Category = { id: number; name: string };
type PostRow = {
    id: number;
    slug: string;
    title: string;
    status: string;
    published_at: string | null;
    updated_at: string;
    category?: { id: number; name: string } | null;
    author?: { id: number; name: string } | null;
};

const props = defineProps<{
    posts: { data: PostRow[]; links: { url: string | null; label: string; active: boolean }[]; from: number; to: number; total: number };
    categories: Category[];
    filters: { status: string | null; category: string | null; q: string | null };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Posts', href: postsRoutes.index().url },
        ],
    },
});

const q = ref(props.filters.q ?? '');
const status = ref(props.filters.status ?? '');
const category = ref(props.filters.category ?? '');

const applyFilters = () => {
    router.get(
        postsRoutes.index().url,
        { q: q.value || undefined, status: status.value || undefined, category: category.value || undefined },
        { preserveState: true, replace: true },
    );
};

const destroy = (post: PostRow) => {
    if (!window.confirm(`Delete "${post.title}"?`)) return;
    router.delete(PostController.destroy.url({ post: post.id }));
};
</script>

<template>
    <Head title="Posts" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
                Blog
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                Posts
            </h1>
        </div>
        <Button as-child variant="marketing" size="marketing">
            <Link :href="postsRoutes.create().url">New post</Link>
        </Button>
    </header>

    <section
        class="mt-8 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-7 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <form class="grid gap-3 sm:grid-cols-[2fr_1fr_1fr_auto]" @submit.prevent="applyFilters">
            <Input v-model="q" placeholder="Search title…" />
            <select
                v-model="status"
                class="rounded-md border border-input bg-background px-3 py-2 text-sm capitalize"
            >
                <option value="">All statuses</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
                <option value="published">Published</option>
            </select>
            <select
                v-model="category"
                class="rounded-md border border-input bg-background px-3 py-2 text-sm"
            >
                <option value="">All categories</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
            <Button type="submit" variant="secondary">Filter</Button>
        </form>
    </section>

    <section
        class="mt-6 overflow-hidden rounded-3xl bg-white ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] dark:bg-white/[0.04] dark:ring-white/10"
    >
        <table class="min-w-full divide-y divide-[var(--color-sand-300)]/60 text-sm dark:divide-white/10">
            <thead class="text-left text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                <tr>
                    <th class="px-5 py-4">Title</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4">Author</th>
                    <th class="px-5 py-4">Published</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-sand-300)]/60 dark:divide-white/10">
                <tr v-for="post in posts.data" :key="post.id" class="transition hover:bg-[var(--color-sand-100)]/40 dark:hover:bg-white/[0.02]">
                    <td class="px-5 py-4 font-medium text-[var(--color-deep-teal)]">
                        <Link :href="postsRoutes.edit(post.id).url" class="hover:underline">{{ post.title }}</Link>
                    </td>
                    <td class="px-5 py-4">
                        <span
                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                            :class="{
                                'bg-[var(--color-emerald-700)]/10 text-[var(--color-emerald-700)]': post.status === 'published',
                                'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300': post.status === 'scheduled',
                                'bg-slate-100 text-slate-700 dark:bg-white/[0.06] dark:text-white/80': post.status === 'draft',
                            }"
                        >{{ post.status }}</span>
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">{{ post.category?.name ?? '—' }}</td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">{{ post.author?.name ?? '—' }}</td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">{{ post.published_at?.slice(0, 10) ?? '—' }}</td>
                    <td class="px-5 py-4 text-right">
                        <Link
                            :href="postsRoutes.edit(post.id).url"
                            class="text-sm font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                        >Edit</Link>
                        <button
                            type="button"
                            class="ml-4 text-sm font-medium text-destructive underline-offset-4 hover:underline"
                            @click="destroy(post)"
                        >Delete</button>
                    </td>
                </tr>
                <tr v-if="posts.data.length === 0">
                    <td colspan="6" class="px-5 py-12 text-center text-slate-500 dark:text-white/60">
                        <p class="font-serif text-xl text-[var(--color-deep-teal)]">No posts yet.</p>
                        <p class="mt-1 text-sm">Click "New post" to create your first one.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <nav
        v-if="posts.total > posts.data.length"
        class="mt-6 flex flex-wrap items-center gap-2 text-sm"
    >
        <template v-for="link in posts.links" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                class="rounded-full px-3 py-1.5 ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:ring-white/15 dark:hover:bg-white/[0.06]"
                :class="{ 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)] dark:bg-[var(--color-emerald-700)] dark:ring-[var(--color-emerald-700)]': link.active }"
                v-html="link.label"
            />
            <span
                v-else
                class="rounded-full px-3 py-1.5 text-slate-400 ring-1 ring-[var(--color-sand-300)]/50 dark:text-white/40 dark:ring-white/10"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
