<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CategoryController from '@/actions/App/Http/Controllers/Admin/CategoryController';
import AdminPageHeader from '@/components/AdminPageHeader.vue';
import AdminTaxonomyTable from '@/components/AdminTaxonomyTable.vue';
import { Button } from '@/components/ui/button';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import categoriesRoutes from '@/routes/admin/categories';

type Category = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    posts_count: number;
};

defineProps<{ categories: Category[] }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Categories', href: categoriesRoutes.index().url },
        ],
    },
});

const confirmDelete = useConfirmDelete();

const destroy = (item: { id: number; name: string }) => {
    confirmDelete(
        CategoryController.destroy.url({ category: item.id }),
        `Delete "${item.name}"?`,
    );
};
</script>

<template>
    <Head title="Categories" />

    <AdminPageHeader eyebrow="Taxonomy" title="Categories">
        <template #actions>
            <Button as-child variant="marketing" size="marketing">
                <Link :href="categoriesRoutes.create().url">New category</Link>
            </Button>
        </template>
    </AdminPageHeader>

    <AdminTaxonomyTable
        :items="categories"
        :edit-url="(id) => categoriesRoutes.edit(id).url"
        empty-title="No categories yet."
        empty-body="Categories group related posts."
        @delete="destroy"
    />
</template>
