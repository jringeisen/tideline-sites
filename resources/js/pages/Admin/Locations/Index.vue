<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import LocationController from '@/actions/App/Http/Controllers/Admin/LocationController';
import AdminPageHeader from '@/components/AdminPageHeader.vue';
import AdminTaxonomyTable from '@/components/AdminTaxonomyTable.vue';
import { Button } from '@/components/ui/button';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import locationsRoutes from '@/routes/admin/locations';

type Location = {
    id: number;
    name: string;
    slug: string;
    services_count: number;
};

defineProps<{ locations: Location[] }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Locations', href: locationsRoutes.index().url },
        ],
    },
});

const confirmDelete = useConfirmDelete();

const destroy = (item: { id: number; name: string }) => {
    confirmDelete(
        LocationController.destroy.url({ location: item.id }),
        `Delete "${item.name}"?`,
    );
};
</script>

<template>
    <Head title="Locations" />

    <AdminPageHeader eyebrow="Marketing" title="Locations">
        <template #actions>
            <Button as-child variant="marketing" size="marketing">
                <Link :href="locationsRoutes.create().url">New location</Link>
            </Button>
        </template>
    </AdminPageHeader>

    <AdminTaxonomyTable
        :items="locations"
        :edit-url="(id) => locationsRoutes.edit(id).url"
        count-label="Services"
        count-key="services_count"
        empty-title="No locations yet."
        empty-body="Locations power the /locations pages and service-area links."
        @delete="destroy"
    />
</template>
