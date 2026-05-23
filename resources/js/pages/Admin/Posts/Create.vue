<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PostController from '@/actions/App/Http/Controllers/Admin/PostController';
import posts from '@/routes/admin/posts';
import PostForm from './components/PostForm.vue';

defineProps<{
    categories: { id: number; name: string }[];
    tags: { id: number; name: string }[];
    statuses: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Posts', href: posts.index().url },
            { title: 'New post', href: posts.create().url },
        ],
    },
});
</script>

<template>
    <Head title="New post" />

    <header>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
            Blog
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            New post
        </h1>
    </header>

    <div class="mt-8">
        <PostForm
            :form-action="PostController.store.form()"
            :categories="categories"
            :tags="tags"
            :statuses="statuses"
            submit-label="Create post"
        />
    </div>
</template>
