<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import categories from '@/routes/admin/categories';

const props = defineProps<{
    category: { id: number; name: string; slug: string; description: string | null };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Categories', href: categories.index().url },
            { title: 'Edit', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit · ${category.name}`" />

    <header>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
            Editing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            {{ category.name }}
        </h1>
    </header>

    <Form
        v-bind="CategoryController.update.form({ category: category.id })"
        class="mt-8 max-w-2xl space-y-5 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input id="name" name="name" :default-value="category.name" required maxlength="100" />
            <InputError :message="errors.name" />
        </div>
        <div class="grid gap-2">
            <Label for="slug">Slug</Label>
            <Input id="slug" name="slug" :default-value="category.slug" maxlength="100" />
            <InputError :message="errors.slug" />
        </div>
        <div class="grid gap-2">
            <Label for="description">Description</Label>
            <textarea
                id="description"
                name="description"
                rows="3"
                maxlength="500"
                :default-value="category.description ?? ''"
                class="rounded-md border border-input bg-background px-3 py-2 text-sm"
            />
            <InputError :message="errors.description" />
        </div>
        <div class="pt-2">
            <Button :disabled="processing" variant="marketing" size="marketing">Save changes</Button>
        </div>
    </Form>
</template>
