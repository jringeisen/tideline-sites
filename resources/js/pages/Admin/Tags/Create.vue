<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TagController from '@/actions/App/Http/Controllers/Admin/TagController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import tags from '@/routes/admin/tags';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tags', href: tags.index().url },
            { title: 'New', href: tags.create().url },
        ],
    },
});
</script>

<template>
    <Head title="New tag" />

    <header>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
            Taxonomy
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            New tag
        </h1>
    </header>

    <Form
        v-bind="TagController.store.form()"
        class="mt-8 max-w-2xl space-y-5 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input id="name" name="name" required maxlength="100" />
            <InputError :message="errors.name" />
        </div>
        <div class="grid gap-2">
            <Label for="slug">Slug (optional)</Label>
            <Input id="slug" name="slug" maxlength="100" />
            <InputError :message="errors.slug" />
        </div>
        <div class="pt-2">
            <Button :disabled="processing" variant="marketing" size="marketing">Create tag</Button>
        </div>
    </Form>
</template>
