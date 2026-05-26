<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import services from '@/routes/admin/services';
import ServiceForm from './components/ServiceForm.vue';

type Faq = { question: string; answer: string };

const props = defineProps<{
    service: {
        id: number;
        name: string;
        slug: string;
        summary: string | null;
        icon: string | null;
        hero_subhead: string | null;
        body: string | null;
        faqs: Faq[] | null;
        meta_title: string | null;
        meta_description: string | null;
        og_image_url: string | null;
        is_published: boolean;
        sort_order: number;
    };
    serviceLocationIds: number[];
    locations: { id: number; name: string }[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Services', href: services.index().url },
            { title: 'Edit service', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit · ${service.name}`" />

    <header>
        <p
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
        >
            Editing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            {{ service.name }}
        </h1>
    </header>

    <div class="mt-8">
        <ServiceForm
            :form-action="
                ServiceController.update.form({ service: service.id })
            "
            :initial="service"
            :initial-faqs="props.service.faqs ?? []"
            :initial-location-ids="serviceLocationIds"
            :locations="locations"
            submit-label="Save changes"
        />
    </div>
</template>
