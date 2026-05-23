<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

const {
    title = '',
    description = '',
    eyebrow,
} = defineProps<{
    title?: string;
    description?: string;
    eyebrow?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :eyebrow="eyebrow">
        <slot />
    </AuthLayout>
</template>
