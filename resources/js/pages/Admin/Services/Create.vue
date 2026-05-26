<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import services from '@/routes/admin/services';
import ServiceForm from './components/ServiceForm.vue';

defineProps<{
    locations: { id: number; name: string }[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Services', href: services.index().url },
            { title: 'New service', href: services.create().url },
        ],
    },
});
</script>

<template>
    <Head title="New service" />

    <header>
        <p
            class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
        >
            Marketing
        </p>
        <h1
            class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
        >
            New service
        </h1>
    </header>

    <div class="mt-8">
        <ServiceForm
            :form-action="ServiceController.store.form()"
            :locations="locations"
            submit-label="Create service"
        />
    </div>
</template>
