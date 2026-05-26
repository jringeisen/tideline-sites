<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import TagController from '@/actions/App/Http/Controllers/Admin/TagController';
import AdminPageHeader from '@/components/AdminPageHeader.vue';
import AdminTaxonomyTable from '@/components/AdminTaxonomyTable.vue';
import { Button } from '@/components/ui/button';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import tagsRoutes from '@/routes/admin/tags';

type Tag = { id: number; name: string; slug: string; posts_count: number };

defineProps<{ tags: Tag[] }>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Tags', href: tagsRoutes.index().url }],
    },
});

const confirmDelete = useConfirmDelete();

const destroy = (item: { id: number; name: string }) => {
    confirmDelete(
        TagController.destroy.url({ tag: item.id }),
        `Delete "${item.name}"?`,
    );
};
</script>

<template>
    <Head title="Tags" />

    <AdminPageHeader eyebrow="Taxonomy" title="Tags">
        <template #actions>
            <Button as-child variant="marketing" size="marketing">
                <Link :href="tagsRoutes.create().url">New tag</Link>
            </Button>
        </template>
    </AdminPageHeader>

    <AdminTaxonomyTable
        :items="tags"
        :edit-url="(id) => tagsRoutes.edit(id).url"
        empty-title="No tags yet."
        empty-body="Tags help readers explore related posts."
        @delete="destroy"
    />
</template>
