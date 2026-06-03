<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlogPostCard from '@/components/marketing/BlogPostCard.vue';
import type { PostCard } from '@/components/marketing/BlogPostCard.vue';
import Pagination from '@/components/marketing/Pagination.vue';

type Taxonomy = { name: string; slug: string };

type Paginator = {
    data: PostCard[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

const props = defineProps<{
    posts: Paginator;
    categories: Taxonomy[];
    tags: Taxonomy[];
    q: string;
    activeCategory: Taxonomy | null;
    activeTag: Taxonomy | null;
}>();

const search = ref(props.q);

function submitSearch(): void {
    router.get(
        '/blog',
        { q: search.value },
        { preserveScroll: true, preserveState: true, replace: true },
    );
}
</script>

<template>
    <div>
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white"
        >
            <div
                class="absolute inset-0 -z-10"
                style="
                    background: linear-gradient(
                        180deg,
                        #1e2e44 0%,
                        #243650 60%,
                        #1a2840 100%
                    );
                "
            />
            <img
                src="/american-flag.webp"
                alt=""
                width="1729"
                height="910"
                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
                loading="eager"
                fetchpriority="high"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"
            />
            <div class="mx-auto max-w-5xl px-6 pt-32 pb-16 sm:pt-40 lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    All American Web Design blog
                </p>
                <h1
                    class="mt-4 font-serif text-5xl leading-[0.95] font-bold tracking-tight uppercase sm:text-6xl"
                >
                    <template v-if="activeCategory">{{
                        activeCategory.name
                    }}</template>
                    <template v-else-if="activeTag"
                        >#{{ activeTag.name }}</template
                    >
                    <template v-else
                        >Notes from the
                        <span class="text-[var(--color-red)]">workshop</span
                        >.</template
                    >
                </h1>
                <p class="mt-6 max-w-2xl text-lg text-white/85">
                    Practical thinking on web design, SEO, and growing a small
                    business online.
                </p>
            </div>
            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <section class="bg-[var(--color-cream)] py-12">
            <div class="mx-auto max-w-5xl px-6 lg:px-8">
                <form
                    class="flex items-center gap-3"
                    @submit.prevent="submitSearch"
                >
                    <label for="q" class="sr-only">Search the blog</label>
                    <input
                        id="q"
                        v-model="search"
                        name="q"
                        type="search"
                        placeholder="Search posts…"
                        class="w-full rounded-full border border-[var(--color-sand-300)] bg-white px-5 py-3 text-sm text-[var(--color-deep-teal)] placeholder:text-slate-400 focus:border-[var(--color-emerald-700)] focus:ring-2 focus:ring-[var(--color-emerald-700)]/30 focus:outline-none dark:bg-white/[0.04] dark:text-white"
                    />
                    <button
                        type="submit"
                        class="inline-flex shrink-0 rounded-full bg-[var(--color-emerald-900)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--color-deep-teal)]"
                    >
                        Search
                    </button>
                </form>

                <div v-if="categories.length" class="mt-8">
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >
                        Categories
                    </p>
                    <ul class="mt-3 flex flex-wrap gap-2">
                        <li>
                            <Link
                                href="/blog"
                                class="inline-flex rounded-full px-3 py-1 text-sm ring-1 transition"
                                :class="
                                    !activeCategory && !activeTag
                                        ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)]'
                                        : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]'
                                "
                            >
                                All
                            </Link>
                        </li>
                        <li v-for="category in categories" :key="category.slug">
                            <Link
                                :href="`/blog/category/${category.slug}`"
                                class="inline-flex rounded-full px-3 py-1 text-sm ring-1 transition"
                                :class="
                                    activeCategory?.slug === category.slug
                                        ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)]'
                                        : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]'
                                "
                            >
                                {{ category.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="bg-[var(--color-cream)] pb-20">
            <div class="mx-auto max-w-5xl px-6 lg:px-8">
                <div
                    v-if="!posts.data.length"
                    class="rounded-2xl border border-dashed border-[var(--color-sand-300)] bg-white p-12 text-center dark:bg-white/[0.04]"
                >
                    <p
                        class="font-serif text-2xl text-[var(--color-deep-teal)]"
                    >
                        No posts found.
                    </p>
                    <p v-if="q !== ''" class="mt-2 text-sm text-slate-600">
                        Nothing matched "{{ q }}". Try a broader query.
                    </p>
                </div>

                <template v-else>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <BlogPostCard
                            v-for="post in posts.data"
                            :key="post.slug"
                            :post="post"
                        />
                    </div>

                    <div class="mt-12">
                        <Pagination :links="posts.links" />
                    </div>
                </template>
            </div>
        </section>
    </div>
</template>
