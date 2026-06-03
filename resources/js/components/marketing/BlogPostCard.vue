<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

export type PostCard = {
    title: string;
    slug: string;
    excerpt: string | null;
    reading_time_minutes: number;
    published_at: string | null;
    published_at_iso: string | null;
    category: { name: string; slug: string } | null;
    author: { name: string | null; avatar_url: string | null };
    tags: Array<{ name: string; slug: string }>;
};

defineProps<{ post: PostCard }>();
</script>

<template>
    <article
        class="group flex flex-col rounded-2xl bg-white p-7 ring-1 ring-[var(--color-sand-300)]/60 transition hover:ring-[var(--color-red)]/30 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <Link
            v-if="post.category"
            :href="`/blog/category/${post.category.slug}`"
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase hover:underline"
        >
            {{ post.category.name }}
        </Link>

        <h3
            class="mt-3 font-serif text-2xl leading-tight tracking-tight text-[var(--color-deep-teal)]"
        >
            <Link :href="`/blog/${post.slug}`" class="hover:underline">{{
                post.title
            }}</Link>
        </h3>

        <p
            v-if="post.excerpt"
            class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-600"
        >
            {{ post.excerpt }}
        </p>

        <div
            class="mt-6 flex items-center justify-between text-xs text-slate-500"
        >
            <div class="flex items-center gap-2">
                <img
                    v-if="post.author.avatar_url"
                    :src="post.author.avatar_url"
                    alt=""
                    class="h-6 w-6 rounded-full object-cover"
                />
                <span>{{ post.author.name ?? 'All American Web Design' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <time :datetime="post.published_at_iso ?? undefined">{{
                    post.published_at
                }}</time>
                <span>· {{ post.reading_time_minutes }} min read</span>
            </div>
        </div>

        <ul v-if="post.tags.length" class="mt-4 flex flex-wrap gap-1.5">
            <li v-for="tag in post.tags" :key="tag.slug">
                <Link
                    :href="`/blog/tag/${tag.slug}`"
                    class="inline-flex rounded-full bg-[var(--color-sand-100)] px-2.5 py-0.5 text-xs text-[var(--color-deep-teal)] hover:bg-[var(--color-sand-200)] dark:bg-white/[0.06] dark:text-white"
                >
                    #{{ tag.name }}
                </Link>
            </li>
        </ul>
    </article>
</template>
