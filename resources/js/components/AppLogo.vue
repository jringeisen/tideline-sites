<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const company = computed(() => usePage().props.company);
</script>

<template>
    <img
        src="/logo-light.webp"
        :alt="company.name"
        width="800"
        height="161"
        class="h-9 w-auto max-w-full shrink-0 object-contain dark:brightness-0 dark:invert"
    />
</template>
