<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LocationController from '@/actions/App/Http/Controllers/Admin/LocationController';
import locations from '@/routes/admin/locations';
import LocationForm from './components/LocationForm.vue';

type Faq = { question: string; answer: string };
type Segment = { title: string; body: string };

const props = defineProps<{
    location: {
        id: number;
        name: string;
        slug: string;
        display_name: string;
        region: string | null;
        tagline: string | null;
        hero_subhead: string | null;
        intro: string | null;
        why_local: string | null;
        body: string | null;
        segments: Segment[] | null;
        faqs: Faq[] | null;
        lat: number | null;
        lng: number | null;
        meta_title: string | null;
        meta_description: string | null;
        og_image_url: string | null;
        is_published: boolean;
        sort_order: number;
    };
    locationNearbyIds: number[];
    locationServiceIds: number[];
    allLocations: { id: number; name: string }[];
    allServices: { id: number; name: string }[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Locations', href: locations.index().url },
            { title: 'Edit location', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit · ${location.name}`" />

    <header>
        <p
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
        >
            Editing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            {{ location.name }}
        </h1>
    </header>

    <div class="mt-8">
        <LocationForm
            :form-action="
                LocationController.update.form({ location: location.id })
            "
            :initial="location"
            :initial-segments="props.location.segments ?? []"
            :initial-faqs="props.location.faqs ?? []"
            :initial-nearby-ids="locationNearbyIds"
            :initial-service-ids="locationServiceIds"
            :all-locations="allLocations"
            :all-services="allServices"
            submit-label="Save changes"
        />
    </div>
</template>
