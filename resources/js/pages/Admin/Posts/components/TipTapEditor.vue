<script setup lang="ts">
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { onBeforeUnmount, watch } from 'vue';

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: { levels: [2, 3, 4] },
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: { rel: 'noopener noreferrer' },
        }),
        Image,
        Placeholder.configure({
            placeholder: props.placeholder || 'Write your post…',
        }),
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-slate max-w-none focus:outline-none min-h-[400px] px-4 py-3 dark:prose-invert',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && value !== editor.value.getHTML()) {
            editor.value.commands.setContent(value, { emitUpdate: false });
        }
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const toggle = (command: string) => {
    if (!editor.value) {
        return;
    }

    const chain = editor.value.chain().focus();

    switch (command) {
        case 'bold':
            chain.toggleBold().run();
            break;
        case 'italic':
            chain.toggleItalic().run();
            break;
        case 'strike':
            chain.toggleStrike().run();
            break;
        case 'h2':
            chain.toggleHeading({ level: 2 }).run();
            break;
        case 'h3':
            chain.toggleHeading({ level: 3 }).run();
            break;
        case 'bulletList':
            chain.toggleBulletList().run();
            break;
        case 'orderedList':
            chain.toggleOrderedList().run();
            break;
        case 'blockquote':
            chain.toggleBlockquote().run();
            break;
        case 'code':
            chain.toggleCode().run();
            break;
        case 'hr':
            chain.setHorizontalRule().run();
            break;
        case 'link': {
            const url = window.prompt(
                'URL',
                editor.value.getAttributes('link').href ?? 'https://',
            );

            if (url === null) {
                return;
            }

            if (url === '') {
                chain.unsetLink().run();

                return;
            }

            chain.setLink({ href: url }).run();
            break;
        }
        case 'image': {
            const url = window.prompt('Image URL', 'https://');

            if (url) {
                chain.setImage({ src: url }).run();
            }

            break;
        }
    }
};

const isActive = (name: string, attrs?: Record<string, unknown>) =>
    editor.value?.isActive(name, attrs) ?? false;
</script>

<template>
    <div class="rounded-md border border-input bg-background">
        <div
            class="flex flex-wrap items-center gap-1 border-b border-input px-2 py-1.5 text-sm"
        >
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('bold') }"
                @click="toggle('bold')"
            >
                B
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 italic hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('italic') }"
                @click="toggle('italic')"
            >
                I
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 line-through hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('strike') }"
                @click="toggle('strike')"
            >
                S
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{
                    'bg-muted font-semibold': isActive('heading', { level: 2 }),
                }"
                @click="toggle('h2')"
            >
                H2
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{
                    'bg-muted font-semibold': isActive('heading', { level: 3 }),
                }"
                @click="toggle('h3')"
            >
                H3
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('bulletList') }"
                @click="toggle('bulletList')"
            >
                • List
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('orderedList') }"
                @click="toggle('orderedList')"
            >
                1. List
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('blockquote') }"
                @click="toggle('blockquote')"
            >
                "
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 font-mono hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('code') }"
                @click="toggle('code')"
            >
                ‹›
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                :class="{ 'bg-muted font-semibold': isActive('link') }"
                @click="toggle('link')"
            >
                Link
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                @click="toggle('image')"
            >
                Image
            </button>
            <button
                type="button"
                class="rounded px-2 py-1 hover:bg-muted"
                @click="toggle('hr')"
            >
                ―
            </button>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>
