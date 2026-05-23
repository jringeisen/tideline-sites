<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        fill="none"
        :class="className"
        v-bind="$attrs"
        aria-hidden="true"
    >
        <circle
            cx="20"
            cy="20"
            r="19"
            stroke="currentColor"
            stroke-width="1.5"
            opacity="0.35"
        />
        <path
            d="M5 23c3.5 0 3.5-3 7-3s3.5 3 7 3 3.5-3 7-3 3.5 3 7 3"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
        />
        <path
            d="M5 17c3.5 0 3.5-3 7-3s3.5 3 7 3 3.5-3 7-3 3.5 3 7 3"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            opacity="0.55"
        />
    </svg>
</template>
