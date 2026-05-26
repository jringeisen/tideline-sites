<script setup lang="ts">
import type { Method } from '@inertiajs/core';
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import TipTapEditor from '@/pages/Admin/Posts/components/TipTapEditor.vue';

type Option = { id: number; name: string };
type Faq = { question: string; answer: string };

const props = defineProps<{
    formAction: { action: string; method: Method; _method?: string };
    initial?: {
        name?: string;
        slug?: string;
        summary?: string | null;
        icon?: string | null;
        hero_subhead?: string | null;
        body?: string | null;
        meta_title?: string | null;
        meta_description?: string | null;
        og_image_url?: string | null;
        is_published?: boolean;
        sort_order?: number;
    };
    initialFaqs?: Faq[];
    initialLocationIds?: number[];
    locations: Option[];
    submitLabel?: string;
}>();

const slugify = (value: string): string =>
    value
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');

const name = ref<string>(props.initial?.name ?? '');
const slug = ref<string>(props.initial?.slug ?? '');
const slugTouched = ref<boolean>(Boolean(props.initial?.slug));
const body = ref<string>(props.initial?.body ?? '');
const isPublished = ref<boolean>(props.initial?.is_published ?? true);
const faqs = ref<Faq[]>((props.initialFaqs ?? []).map((f) => ({ ...f })));
const locationIds = ref<number[]>([...(props.initialLocationIds ?? [])]);

const onNameInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    name.value = value;

    if (!slugTouched.value) {
        slug.value = slugify(value);
    }
};

const onSlugInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    slug.value = value;
    slugTouched.value = value.length > 0;
};

const addFaq = () => faqs.value.push({ question: '', answer: '' });
const removeFaq = (i: number) => faqs.value.splice(i, 1);

const toggleLocation = (id: number) => {
    locationIds.value = locationIds.value.includes(id)
        ? locationIds.value.filter((l) => l !== id)
        : [...locationIds.value, id];
};

const transform = (data: Record<string, unknown>) => ({
    ...data,
    body: body.value,
    is_published: isPublished.value,
    faqs: faqs.value.filter((f) => f.question.trim() || f.answer.trim()),
    locations: locationIds.value,
});

const cardClass =
    'space-y-5 rounded-3xl bg-white p-6 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10';
const inputClass =
    'rounded-md border border-input bg-background px-3 py-2 text-sm';
</script>

<template>
    <Form
        v-bind="formAction"
        :transform="transform"
        class="space-y-8"
        v-slot="{ errors, processing }"
    >
        <section :class="cardClass">
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    :model-value="name"
                    required
                    maxlength="200"
                    @input="onNameInput"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug (optional — auto from name)</Label>
                <Input
                    id="slug"
                    name="slug"
                    :model-value="slug"
                    maxlength="200"
                    @input="onSlugInput"
                />
                <InputError :message="errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="summary">Summary</Label>
                <textarea
                    id="summary"
                    name="summary"
                    rows="2"
                    maxlength="500"
                    :default-value="initial?.summary ?? ''"
                    :class="inputClass"
                />
                <InputError :message="errors.summary" />
            </div>

            <div class="grid gap-2">
                <Label for="hero_subhead">Hero subhead</Label>
                <textarea
                    id="hero_subhead"
                    name="hero_subhead"
                    rows="2"
                    maxlength="255"
                    :default-value="initial?.hero_subhead ?? ''"
                    :class="inputClass"
                />
                <InputError :message="errors.hero_subhead" />
            </div>

            <div class="grid gap-2">
                <Label for="icon">Icon (SVG path data)</Label>
                <textarea
                    id="icon"
                    name="icon"
                    rows="2"
                    maxlength="2000"
                    :default-value="initial?.icon ?? ''"
                    :class="`${inputClass} font-mono`"
                />
                <InputError :message="errors.icon" />
            </div>

            <div class="grid gap-2">
                <Label>Body</Label>
                <TipTapEditor
                    v-model="body"
                    placeholder="Describe the service in depth…"
                />
                <InputError :message="errors.body" />
            </div>
        </section>

        <section :class="cardClass">
            <div class="flex items-center justify-between">
                <h3
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                >
                    FAQs
                </h3>
                <Button type="button" variant="secondary" @click="addFaq">
                    Add FAQ
                </Button>
            </div>

            <div
                v-for="(faq, i) in faqs"
                :key="i"
                class="grid gap-2 rounded-2xl bg-[var(--color-sand-100)]/50 p-4 dark:bg-white/[0.02]"
            >
                <Input v-model="faq.question" placeholder="Question" />
                <textarea
                    v-model="faq.answer"
                    rows="2"
                    placeholder="Answer"
                    :class="inputClass"
                />
                <div class="text-right">
                    <button
                        type="button"
                        class="text-sm font-medium text-destructive underline-offset-4 hover:underline"
                        @click="removeFaq(i)"
                    >
                        Remove
                    </button>
                </div>
            </div>
            <p v-if="faqs.length === 0" class="text-sm text-muted-foreground">
                No FAQs yet.
            </p>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <div :class="cardClass">
                <h3
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                >
                    Publishing
                </h3>

                <label class="flex items-center gap-2 text-sm">
                    <input v-model="isPublished" type="checkbox" />
                    Published
                </label>

                <div class="grid gap-2">
                    <Label for="sort_order">Sort order</Label>
                    <Input
                        id="sort_order"
                        name="sort_order"
                        type="number"
                        min="0"
                        :default-value="initial?.sort_order ?? 0"
                    />
                    <InputError :message="errors.sort_order" />
                </div>

                <div class="grid gap-2">
                    <Label>Offered in</Label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="location in locations"
                            :key="location.id"
                            type="button"
                            class="rounded-full px-3 py-1 text-xs ring-1 transition"
                            :class="
                                locationIds.includes(location.id)
                                    ? 'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)] dark:bg-[var(--color-emerald-700)] dark:ring-[var(--color-emerald-700)]'
                                    : 'bg-white text-[var(--color-deep-teal)] ring-[var(--color-sand-300)] hover:bg-[var(--color-sand-100)] dark:bg-white/[0.04] dark:text-white dark:ring-white/15 dark:hover:bg-white/[0.08]'
                            "
                            @click="toggleLocation(location.id)"
                        >
                            {{ location.name }}
                        </button>
                        <span
                            v-if="locations.length === 0"
                            class="text-xs text-muted-foreground"
                            >No locations yet.</span
                        >
                    </div>
                </div>
            </div>

            <div :class="cardClass">
                <h3
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                >
                    SEO
                </h3>

                <div class="grid gap-2">
                    <Label for="meta_title">Meta title</Label>
                    <Input
                        id="meta_title"
                        name="meta_title"
                        :default-value="initial?.meta_title ?? ''"
                        maxlength="70"
                    />
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
                        :class="inputClass"
                    />
                    <InputError :message="errors.meta_description" />
                </div>

                <div class="grid gap-2">
                    <Label for="og_image_url">OG image URL</Label>
                    <Input
                        id="og_image_url"
                        name="og_image_url"
                        :default-value="initial?.og_image_url ?? ''"
                    />
                    <InputError :message="errors.og_image_url" />
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <Button :disabled="processing" variant="marketing" size="marketing">
                {{ submitLabel ?? 'Save service' }}
            </Button>
        </div>
    </Form>
</template>
