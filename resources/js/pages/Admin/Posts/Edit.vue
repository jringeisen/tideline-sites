<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PostController from '@/actions/App/Http/Controllers/Admin/PostController';
import posts from '@/routes/admin/posts';
import PostForm from './components/PostForm.vue';

defineProps<{
    post: {
        id: number;
        slug: string;
        title: string;
        excerpt: string | null;
        content: string;
        category_id: number | null;
        status: string;
        published_at: string | null;
        meta_title: string | null;
        meta_description: string | null;
        og_image_url: string | null;
    };
    postTags: number[];
    categories: { id: number; name: string }[];
    tags: { id: number; name: string }[];
    statuses: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Posts', href: posts.index().url },
            { title: 'Edit post', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit · ${post.title}`" />

    <header>
        <p
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
        >
            Editing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            {{ post.title }}
        </h1>
    </header>

    <div class="mt-8">
        <PostForm
            :form-action="PostController.update.form({ post: post.id })"
            :initial="post"
            :initial-tag-ids="postTags"
            :categories="categories"
            :tags="tags"
            :statuses="statuses"
            submit-label="Save changes"
        />
    </div>
</template>
