<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{ color?: 'white' | 'emerald' }>(), {
    color: 'white',
});

const colorClass = computed(() =>
    props.color === 'emerald'
        ? 'text-[var(--color-emerald-600)]'
        : 'text-[var(--color-emerald-200)]',
);
</script>

<template>
    <svg
        viewBox="0 0 20 20"
        fill="currentColor"
        class="mt-0.5 h-4 w-4 flex-none"
        :class="colorClass"
        aria-hidden="true"
    >
        <path
            fill-rule="evenodd"
            d="M16.704 5.29a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 011.06-1.06l2.97 2.97 6.97-6.97a.75.75 0 011.06 0z"
            clip-rule="evenodd"
        />
    </svg>
</template>
