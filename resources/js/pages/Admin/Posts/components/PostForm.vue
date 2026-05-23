<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import TipTapEditor from './TipTapEditor.vue';

type Option = { id: number; name: string };

const props = defineProps<{
    formAction: { action: string; method: string; _method?: string };
    initial?: {
        title?: string;
        slug?: string;
        excerpt?: string | null;
        content?: string;
        category_id?: number | null;
        status?: string;
        published_at?: string | null;
        meta_title?: string | null;
        meta_description?: string | null;
        og_image_url?: string | null;
    };
    initialTagIds?: number[];
    categories: Option[];
    tags: Option[];
    statuses: string[];
    submitLabel?: string;
}>();

const content = ref<string>(props.initial?.content ?? '');
const tagIds = ref<number[]>([...(props.initialTagIds ?? [])]);

const publishedAtForInput = computed(() => {
    const value = props.initial?.published_at;
    if (!value) return '';
    return value.slice(0, 16);
});

const toggleTag = (id: number) => {
    if (tagIds.value.includes(id)) {
        tagIds.value = tagIds.value.filter((t) => t !== id);
    } else {
        tagIds.value = [...tagIds.value, id];
    }
};
</script>

<template>
    <Form
        v-bind="formAction"
        :transform="(data: Record<string, unknown>) => ({ ...data, content, tags: tagIds })"
        class="space-y-8"
        v-slot="{ errors, processing }"
    >
        <section class="space-y-5 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-8 dark:bg-white/[0.04] dark:ring-white/10">
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input id="title" name="title" :default-value="initial?.title" required maxlength="200" />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug (optional — auto from title)</Label>
                <Input id="slug" name="slug" :default-value="initial?.slug" maxlength="200" />
                <InputError :message="errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="excerpt">Excerpt</Label>
                <textarea
                    id="excerpt"
                    name="excerpt"
                    rows="2"
                    maxlength="500"
                    :default-value="initial?.excerpt ?? ''"
                    class="rounded-md border border-input bg-background px-3 py-2 text-sm"
                />
                <InputError :message="errors.excerpt" />
            </div>

            <div class="grid gap-2">
                <Label>Content</Label>
                <TipTapEditor v-model="content" placeholder="Write the post…" />
                <input type="hidden" name="content" :value="content" />
                <InputError :message="errors.content" />
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <div class="space-y-5 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-7 dark:bg-white/[0.04] dark:ring-white/10">
                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">Publishing</h3>

                <div class="grid gap-2">
                    <Label for="status">Status</Label>
                    <select
                        id="status"
                        name="status"
                        :default-value="initial?.status ?? 'draft'"
                        class="rounded-md border border-input bg-background px-3 py-2 text-sm capitalize"
                    >
                        <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                    </select>
                    <InputError :message="errors.status" />
                </div>

                <div class="grid gap-2">
                    <Label for="published_at">Publish date</Label>
                    <Input
                        id="published_at"
                        name="published_at"
                        type="datetime-local"
                        :default-value="publishedAtForInput"
                    />
                    <InputError :message="errors.published_at" />
                </div>

                <div class="grid gap-2">
                    <Label for="category_id">Category</Label>
                    <select
                        id="category_id"
                        name="category_id"
                        :default-value="initial?.category_id ?? ''"
                        class="rounded-md border border-input bg-background px-3 py-2 text-sm"
                    >
                        <option value="">— None —</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <InputError :message="errors.category_id" />
                </div>

                <div class="grid gap-2">
                    <Label>Tags</Label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="tag in tags"
                            :key="tag.id"
                            type="button"
                            class="rounded-full px-3 py-1 text-xs ring-1 transition"
                            :class="
                                tagIds.includes(tag.id)
                                    ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)] dark:bg-[var(--color-emerald-700)] dark:ring-[var(--color-emerald-700)]'
                                    : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]'
                            "
                            @click="toggleTag(tag.id)"
                        >
                            {{ tag.name }}
                        </button>
                        <span v-if="tags.length === 0" class="text-xs text-muted-foreground">No tags yet.</span>
                    </div>
                </div>
            </div>

            <div class="space-y-5 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-7 dark:bg-white/[0.04] dark:ring-white/10">
                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">SEO</h3>

                <div class="grid gap-2">
                    <Label for="meta_title">Meta title</Label>
                    <Input id="meta_title" name="meta_title" :default-value="initial?.meta_title ?? ''" maxlength="70" />
                    <InputError :message="errors.meta_title" />
                </div>

                <div class="grid gap-2">
                    <Label for="meta_description">Meta description</Label>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        rows="2"
                        maxlength="200"
                        :default-value="initial?.meta_description ?? ''"
                        class="rounded-md border border-input bg-background px-3 py-2 text-sm"
                    />
                    <InputError :message="errors.meta_description" />
                </div>

                <div class="grid gap-2">
                    <Label for="og_image_url">OG image URL</Label>
                    <Input id="og_image_url" name="og_image_url" :default-value="initial?.og_image_url ?? ''" />
                    <InputError :message="errors.og_image_url" />
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <Button :disabled="processing" variant="marketing" size="marketing">{{ submitLabel ?? 'Save post' }}</Button>
        </div>
    </Form>
</template>
