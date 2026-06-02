<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ContactInquiryController from '@/actions/App/Http/Controllers/Admin/ContactInquiryController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { inquirySourceLabel } from '@/lib/inquiry';
import contactInquiriesRoutes from '@/routes/admin/contact-inquiries';

type InquiryRow = {
    id: number;
    name: string;
    email: string;
    business_name: string | null;
    website: string | null;
    phone: string | null;
    plan: string | null;
    source: string;
    read_at: string | null;
    is_spam: boolean;
    created_at: string;
};

const props = defineProps<{
    inquiries: {
        data: InquiryRow[];
        links: { url: string | null; label: string; active: boolean }[];
        from: number;
        to: number;
        total: number;
    };
    filters: {
        q: string | null;
        unread: boolean;
        source: string | null;
        show_spam: boolean;
    };
    unreadCount: number;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Contact inquiries',
                href: contactInquiriesRoutes.index().url,
            },
        ],
    },
});

const q = ref(props.filters.q ?? '');
const unread = ref(props.filters.unread);
const source = ref(props.filters.source ?? '');
const showSpam = ref(props.filters.show_spam);

const applyFilters = () => {
    router.get(
        contactInquiriesRoutes.index().url,
        {
            q: q.value || undefined,
            unread: unread.value ? 1 : undefined,
            source: source.value || undefined,
            show_spam: showSpam.value ? 1 : undefined,
        },
        { preserveState: true, replace: true },
    );
};

const destroy = (inquiry: InquiryRow) => {
    if (!window.confirm(`Delete inquiry from "${inquiry.name}"?`)) {
        return;
    }

    router.delete(
        ContactInquiryController.destroy.url({ contact_inquiry: inquiry.id }),
    );
};
</script>

<template>
    <Head title="Contact inquiries" />

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p
                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
            >
                Inbox
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                Contact inquiries
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-white/70">
                {{ unreadCount }} unread of {{ inquiries.total }} total
            </p>
        </div>
    </header>

    <section
        class="mt-8 rounded-3xl bg-white p-6 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-7 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <form
            class="grid gap-3 sm:grid-cols-[2fr_auto_auto_auto_auto]"
            @submit.prevent="applyFilters"
        >
            <Input
                v-model="q"
                placeholder="Search name, email, business, or message…"
            />
            <select
                v-model="source"
                class="rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs"
            >
                <option value="">All sources</option>
                <option value="contact">Contact</option>
                <option value="seo_assessment">SEO Assessment</option>
                <option value="seo_report">SEO Report</option>
            </select>
            <label class="inline-flex items-center gap-2 text-sm">
                <input
                    v-model="unread"
                    type="checkbox"
                    class="h-4 w-4 rounded border-[var(--color-sand-300)]"
                />
                Unread only
            </label>
            <label class="inline-flex items-center gap-2 text-sm">
                <input
                    v-model="showSpam"
                    type="checkbox"
                    class="h-4 w-4 rounded border-[var(--color-sand-300)]"
                />
                Show spam
            </label>
            <Button type="submit" variant="secondary">Filter</Button>
        </form>
    </section>

    <section
        class="mt-6 overflow-hidden rounded-3xl bg-white shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 dark:bg-white/[0.04] dark:ring-white/10"
    >
        <table
            class="min-w-full divide-y divide-[var(--color-sand-300)]/60 text-sm dark:divide-white/10"
        >
            <thead
                class="text-left text-xs font-semibold tracking-[0.12em] text-[var(--color-emerald-700)] uppercase"
            >
                <tr>
                    <th class="px-5 py-4">Name</th>
                    <th class="px-5 py-4">Email</th>
                    <th class="px-5 py-4">Source</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Received</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody
                class="divide-y divide-[var(--color-sand-300)]/60 dark:divide-white/10"
            >
                <tr
                    v-for="inquiry in inquiries.data"
                    :key="inquiry.id"
                    class="transition hover:bg-[var(--color-sand-100)]/40 dark:hover:bg-white/[0.02]"
                    :class="{ 'font-semibold': !inquiry.read_at }"
                >
                    <td class="px-5 py-4 text-[var(--color-deep-teal)]">
                        <Link
                            :href="contactInquiriesRoutes.show(inquiry.id).url"
                            class="hover:underline"
                            >{{ inquiry.name }}</Link
                        >
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ inquiry.email }}
                    </td>
                    <td class="px-5 py-4">
                        <span
                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="
                                inquiry.source === 'seo_assessment'
                                    ? 'bg-[var(--color-emerald-700)]/10 text-[var(--color-emerald-700)] dark:bg-[var(--color-emerald-700)]/40 dark:text-white'
                                    : inquiry.source === 'seo_report'
                                      ? 'bg-[var(--color-red)]/10 text-[var(--color-red-deep)] dark:bg-[var(--color-red)]/40 dark:text-white'
                                      : 'bg-slate-100 text-slate-700 dark:bg-white/[0.06] dark:text-white/80'
                            "
                        >
                            {{ inquirySourceLabel(inquiry.source) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span
                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="
                                inquiry.read_at
                                    ? 'bg-slate-100 text-slate-700 dark:bg-white/[0.06] dark:text-white/80'
                                    : 'bg-[var(--color-emerald-700)]/10 text-[var(--color-emerald-700)] dark:bg-[var(--color-emerald-700)]/40 dark:text-white'
                            "
                        >
                            {{ inquiry.read_at ? 'Read' : 'Unread' }}
                        </span>
                        <span
                            v-if="inquiry.is_spam"
                            class="ml-2 inline-flex rounded-full bg-destructive px-2.5 py-0.5 text-xs font-medium text-white"
                        >
                            Spam
                        </span>
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-white/70">
                        {{ inquiry.created_at.slice(0, 10) }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <Link
                            :href="contactInquiriesRoutes.show(inquiry.id).url"
                            class="text-sm font-medium text-[var(--color-emerald-700)] underline-offset-4 hover:underline"
                            >View</Link
                        >
                        <button
                            type="button"
                            class="ml-4 text-sm font-medium text-destructive underline-offset-4 hover:underline"
                            @click="destroy(inquiry)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                <tr v-if="inquiries.data.length === 0">
                    <td
                        colspan="6"
                        class="px-5 py-12 text-center text-slate-500 dark:text-white/60"
                    >
                        <p
                            class="font-serif text-xl text-[var(--color-deep-teal)]"
                        >
                            No inquiries match.
                        </p>
                        <p class="mt-1 text-sm">
                            Try clearing the filters or check back when new
                            inquiries arrive.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <nav
        v-if="inquiries.total > inquiries.data.length"
        class="mt-6 flex flex-wrap items-center gap-2 text-sm"
    >
        <template v-for="link in inquiries.links" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                class="rounded-full px-3 py-1.5 ring-1 ring-[var(--color-sand-300)] transition hover:bg-[var(--color-sand-100)] dark:ring-white/15 dark:hover:bg-white/[0.06]"
                :class="{
                    'bg-[var(--color-emerald-900)] text-white ring-[var(--color-emerald-900)] dark:bg-[var(--color-emerald-700)] dark:ring-[var(--color-emerald-700)]':
                        link.active,
                }"
            >
                <span v-html="link.label" />
            </Link>
            <span
                v-else
                class="rounded-full px-3 py-1.5 text-slate-400 ring-1 ring-[var(--color-sand-300)]/50 dark:text-white/40 dark:ring-white/10"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
