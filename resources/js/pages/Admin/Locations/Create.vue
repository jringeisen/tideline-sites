<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LocationController from '@/actions/App/Http/Controllers/Admin/LocationController';
import locations from '@/routes/admin/locations';
import LocationForm from './components/LocationForm.vue';

defineProps<{
    allLocations: { id: number; name: string }[];
    allServices: { id: number; name: string }[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Locations', href: locations.index().url },
            { title: 'New location', href: locations.create().url },
        ],
    },
});
</script>

<template>
    <Head title="New location" />

    <header>
        <p
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
        >
            Marketing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            New location
        </h1>
    </header>

    <div class="mt-8">
        <LocationForm
            :form-action="LocationController.store.form()"
            :all-locations="allLocations"
            :all-services="allServices"
            submit-label="Create location"
        />
    </div>
</template>
