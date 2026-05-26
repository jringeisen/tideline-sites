<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import AdminPageHeader from '@/components/AdminPageHeader.vue';
import AdminTaxonomyTable from '@/components/AdminTaxonomyTable.vue';
import { Button } from '@/components/ui/button';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import servicesRoutes from '@/routes/admin/services';

type Service = {
    id: number;
    name: string;
    slug: string;
    locations_count: number;
};

defineProps<{ services: Service[] }>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Services', href: servicesRoutes.index().url }],
    },
});

const confirmDelete = useConfirmDelete();

const destroy = (item: { id: number; name: string }) => {
    confirmDelete(
        ServiceController.destroy.url({ service: item.id }),
        `Delete "${item.name}"?`,
    );
};
</script>

<template>
    <Head title="Services" />

    <AdminPageHeader eyebrow="Marketing" title="Services">
        <template #actions>
            <Button as-child variant="marketing" size="marketing">
                <Link :href="servicesRoutes.create().url">New service</Link>
            </Button>
        </template>
    </AdminPageHeader>

    <AdminTaxonomyTable
        :items="services"
        :edit-url="(id) => servicesRoutes.edit(id).url"
        count-label="Locations"
        count-key="locations_count"
        empty-title="No services yet."
        empty-body="Services power the /services pages and homepage section."
        @delete="destroy"
    />
</template>
