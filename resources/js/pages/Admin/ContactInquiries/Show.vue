<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import ContactInquiryController from '@/actions/App/Http/Controllers/Admin/ContactInquiryController';
import { Button } from '@/components/ui/button';
import contactInquiriesRoutes from '@/routes/admin/contact-inquiries';

type Inquiry = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    plan: string | null;
    message: string;
    read_at: string | null;
    created_at: string;
};

const props = defineProps<{ inquiry: Inquiry }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Contact inquiries', href: '/admin/contact-inquiries' },
            { title: 'View', href: '#' },
        ],
    },
});

const toggleRead = () => {
    if (props.inquiry.read_at) {
        router.patch(ContactInquiryController.markUnread.url({ contact_inquiry: props.inquiry.id }));
    } else {
        router.patch(ContactInquiryController.markRead.url({ contact_inquiry: props.inquiry.id }));
    }
};

const destroy = () => {
    if (!window.confirm(`Delete inquiry from "${props.inquiry.name}"?`)) return;
    router.delete(ContactInquiryController.destroy.url({ contact_inquiry: props.inquiry.id }));
};
</script>

<template>
    <Head :title="`Inquiry — ${inquiry.name}`" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-emerald-700)]">
                Inbox
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                {{ inquiry.name }}
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-white/70">
                Received {{ inquiry.created_at.slice(0, 16).replace('T', ' ') }}
                <span
                    class="ml-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium"
                    :class="
                        inquiry.read_at
                            ? 'bg-slate-100 text-slate-700 dark:bg-white/[0.06] dark:text-white/80'
                            : 'bg-[var(--color-emerald-700)]/10 text-[var(--color-emerald-700)]'
                    "
                >
                    {{ inquiry.read_at ? 'Read' : 'Unread' }}
                </span>
            </p>
        </div>
        <div class="flex gap-3">
            <Button as-child variant="secondary">
                <Link :href="contactInquiriesRoutes.index().url">Back to list</Link>
            </Button>
            <Button variant="secondary" @click="toggleRead">
                {{ inquiry.read_at ? 'Mark unread' : 'Mark read' }}
            </Button>
            <Button variant="destructive" @click="destroy">Delete</Button>
        </div>
    </header>

    <section
        class="mt-8 grid gap-6 rounded-3xl bg-white p-6 ring-1 ring-[var(--color-sand-300)]/60 shadow-[0_1px_0_rgba(11,42,46,0.04)] sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                    Email
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    <a class="hover:underline" :href="`mailto:${inquiry.email}`">{{ inquiry.email }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                    Phone
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    {{ inquiry.phone ?? '—' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                    Plan
                </dt>
                <dd class="mt-1 capitalize text-[var(--color-deep-teal)] dark:text-white">
                    {{ inquiry.plan ?? '—' }}
                </dd>
            </div>
        </dl>

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--color-emerald-700)]">
                Message
            </p>
            <div
                class="mt-2 whitespace-pre-wrap rounded-2xl bg-[var(--color-sand-100)]/50 p-5 text-[var(--color-deep-teal)] dark:bg-white/[0.04] dark:text-white"
            >
                {{ inquiry.message }}
            </div>
        </div>
    </section>
</template>
