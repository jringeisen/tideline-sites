<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import ContactInquiryController from '@/actions/App/Http/Controllers/Admin/ContactInquiryController';
import { Button } from '@/components/ui/button';
import { inquirySourceLabel } from '@/lib/inquiry';
import contactInquiriesRoutes from '@/routes/admin/contact-inquiries';

type Inquiry = {
    id: number;
    name: string;
    email: string;
    business_name: string | null;
    website: string | null;
    phone: string | null;
    plan: string | null;
    is_veteran: boolean;
    source: string;
    message: string | null;
    ip_address: string | null;
    read_at: string | null;
    is_spam: boolean;
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

const sourceLabel = computed(() => inquirySourceLabel(props.inquiry.source));

const toggleRead = () => {
    if (props.inquiry.read_at) {
        router.patch(
            ContactInquiryController.markUnread.url({
                contact_inquiry: props.inquiry.id,
            }),
        );
    } else {
        router.patch(
            ContactInquiryController.markRead.url({
                contact_inquiry: props.inquiry.id,
            }),
        );
    }
};

const toggleSpam = () => {
    if (props.inquiry.is_spam) {
        router.patch(
            ContactInquiryController.markNotSpam.url({
                contact_inquiry: props.inquiry.id,
            }),
        );
    } else {
        router.patch(
            ContactInquiryController.markSpam.url({
                contact_inquiry: props.inquiry.id,
            }),
        );
    }
};

const destroy = () => {
    if (!window.confirm(`Delete inquiry from "${props.inquiry.name}"?`)) {
        return;
    }

    router.delete(
        ContactInquiryController.destroy.url({
            contact_inquiry: props.inquiry.id,
        }),
    );
};
</script>

<template>
    <Head :title="`Inquiry — ${inquiry.name}`" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p
                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
            >
                {{ sourceLabel }}
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
                <span
                    v-if="inquiry.is_veteran"
                    class="ml-2 inline-flex rounded-full bg-[var(--color-red)] px-2.5 py-0.5 text-xs font-semibold text-white"
                >
                    U.S. Veteran · 20% off
                </span>
                <span
                    v-if="inquiry.is_spam"
                    class="ml-2 inline-flex rounded-full bg-destructive px-2.5 py-0.5 text-xs font-semibold text-white"
                >
                    Spam
                </span>
            </p>
        </div>
        <div class="flex gap-3">
            <Button as-child variant="secondary">
                <Link :href="contactInquiriesRoutes.index().url"
                    >Back to list</Link
                >
            </Button>
            <Button variant="secondary" @click="toggleRead">
                {{ inquiry.read_at ? 'Mark unread' : 'Mark read' }}
            </Button>
            <Button variant="secondary" @click="toggleSpam">
                {{ inquiry.is_spam ? 'Not spam' : 'Mark spam' }}
            </Button>
            <Button variant="destructive" @click="destroy">Delete</Button>
        </div>
    </header>

    <section
        class="mt-8 grid gap-6 rounded-3xl bg-white p-6 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    Email
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    <a
                        class="hover:underline"
                        :href="`mailto:${inquiry.email}`"
                        >{{ inquiry.email }}</a
                    >
                </dd>
            </div>
            <div v-if="inquiry.business_name">
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    Business
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    {{ inquiry.business_name }}
                </dd>
            </div>
            <div v-if="inquiry.website">
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    Website
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    <a
                        class="hover:underline"
                        :href="inquiry.website"
                        target="_blank"
                        rel="noopener noreferrer"
                        >{{ inquiry.website }}</a
                    >
                </dd>
            </div>
            <div v-if="inquiry.phone">
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    Phone
                </dt>
                <dd class="mt-1 text-[var(--color-deep-teal)] dark:text-white">
                    {{ inquiry.phone }}
                </dd>
            </div>
            <div v-if="inquiry.plan">
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    Plan
                </dt>
                <dd
                    class="mt-1 text-[var(--color-deep-teal)] capitalize dark:text-white"
                >
                    {{ inquiry.plan }}
                </dd>
            </div>
            <div v-if="inquiry.ip_address">
                <dt
                    class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
                >
                    IP address
                </dt>
                <dd
                    class="mt-1 font-mono text-sm text-[var(--color-deep-teal)] dark:text-white"
                >
                    {{ inquiry.ip_address }}
                </dd>
            </div>
        </dl>

        <div v-if="inquiry.message">
            <p
                class="text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
            >
                Message
            </p>
            <div
                class="mt-2 rounded-2xl bg-[var(--color-sand-100)]/50 p-5 whitespace-pre-wrap text-[var(--color-deep-teal)] dark:bg-white/[0.04] dark:text-white"
            >
                {{ inquiry.message }}
            </div>
        </div>
    </section>
</template>
