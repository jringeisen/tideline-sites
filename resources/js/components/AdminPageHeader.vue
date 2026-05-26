<script setup lang="ts">
defineProps<{
    eyebrow: string;
    title: string;
}>();
</script>

<template>
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p
                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
            >
                {{ eyebrow }}
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                {{ title }}
            </h1>
        </div>
        <slot name="actions" />
    </header>
</template>
