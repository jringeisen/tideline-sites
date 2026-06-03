<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BlogPostCard from '@/components/marketing/BlogPostCard.vue';
import type { PostCard } from '@/components/marketing/BlogPostCard.vue';

type Post = PostCard & {
    content: string;
    published_at_full: string | null;
    author: {
        name: string | null;
        avatar_url: string | null;
        bio: string | null;
    };
};

const props = defineProps<{
    post: Post;
    related: PostCard[];
}>();

const page = usePage();

const canonical = computed<string>(
    () =>
        (page.props.meta as { canonical?: string } | undefined)?.canonical ??
        page.url,
);

const shareText = computed(() => encodeURIComponent(props.post.title));
const shareUrl = computed(() => encodeURIComponent(canonical.value));
</script>

<template>
    <article class="bg-[var(--color-cream)]">
        <header
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
            <div class="mx-auto max-w-3xl px-6 pt-32 pb-20 sm:pt-40 lg:px-8">
                <nav aria-label="Breadcrumb" class="text-sm text-white/60">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li>
                            <Link href="/" class="hover:text-white">Home</Link>
                        </li>
                        <li aria-hidden="true">›</li>
                        <li>
                            <Link href="/blog" class="hover:text-white"
                                >Blog</Link
                            >
                        </li>
                        <template v-if="post.category">
                            <li aria-hidden="true">›</li>
                            <li>
                                <Link
                                    :href="`/blog/category/${post.category.slug}`"
                                    class="hover:text-white"
                                    >{{ post.category.name }}</Link
                                >
                            </li>
                        </template>
                    </ol>
                </nav>
                <h1
                    class="mt-6 font-serif text-4xl leading-tight font-bold tracking-tight sm:text-5xl"
                >
                    {{ post.title }}
                </h1>
                <div
                    class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-white/75"
                >
                    <span>{{
                        post.author.name ?? 'All American Web Design'
                    }}</span>
                    <time :datetime="post.published_at_iso ?? undefined">{{
                        post.published_at_full
                    }}</time>
                    <span>{{ post.reading_time_minutes }} min read</span>
                </div>
            </div>

            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </header>

        <div class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
            <div
                class="prose max-w-none prose-slate dark:prose-invert prose-headings:font-serif prose-headings:text-[var(--color-deep-teal)] prose-a:text-[var(--color-emerald-700)] prose-img:rounded-xl"
                v-html="post.content"
            />

            <ul v-if="post.tags.length" class="mt-12 flex flex-wrap gap-2">
                <li v-for="tag in post.tags" :key="tag.slug">
                    <Link
                        :href="`/blog/tag/${tag.slug}`"
                        class="inline-flex rounded-full bg-[var(--color-sand-100)] px-3 py-1 text-sm text-[var(--color-deep-teal)] hover:bg-[var(--color-sand-200)] dark:bg-white/[0.06] dark:text-white"
                    >
                        #{{ tag.name }}
                    </Link>
                </li>
            </ul>

            <section
                aria-label="Share this post"
                class="mt-12 flex flex-wrap items-center gap-3 border-y border-[var(--color-sand-300)] py-6"
            >
                <span
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                    >Share</span
                >
                <a
                    :href="`https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]"
                    >Twitter</a
                >
                <a
                    :href="`https://www.linkedin.com/sharing/share-offsite/?url=${shareUrl}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]"
                    >LinkedIn</a
                >
                <a
                    :href="`https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]"
                    >Facebook</a
                >
                <a
                    :href="`mailto:?subject=${shareText}&body=${shareUrl}`"
                    class="rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-deep-teal)] ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]"
                    >Email</a
                >
            </section>

            <aside
                v-if="post.author.bio"
                class="mt-12 flex items-start gap-5 rounded-2xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
            >
                <img
                    v-if="post.author.avatar_url"
                    :src="post.author.avatar_url"
                    alt=""
                    class="h-14 w-14 rounded-full object-cover"
                />
                <div>
                    <p class="font-serif text-lg text-[var(--color-deep-teal)]">
                        {{ post.author.name }}
                    </p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-600">
                        {{ post.author.bio }}
                    </p>
                </div>
            </aside>
        </div>

        <section v-if="related.length" class="bg-[var(--color-sand-100)] py-16">
            <div class="mx-auto max-w-5xl px-6 lg:px-8">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-red)] uppercase"
                >
                    Keep reading
                </p>
                <h2
                    class="mt-3 font-serif text-3xl font-bold tracking-tight text-[var(--color-deep-teal)] uppercase"
                >
                    More from the blog
                </h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    <BlogPostCard
                        v-for="item in related"
                        :key="item.slug"
                        :post="item"
                    />
                </div>
            </div>
        </section>
    </article>
</template>
