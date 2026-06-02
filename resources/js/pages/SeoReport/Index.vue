<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

interface ReportItem {
    title: string;
    priority: 'high' | 'medium' | 'low';
    category: string | null;
    detail: string;
}

interface ReportSection {
    title: string;
    items: ReportItem[];
}

interface Tip {
    title: string;
    detail: string;
}

interface GbpStep {
    title: string;
    detail: string;
}

interface Report {
    score: number;
    summary: string;
    sections: ReportSection[];
    industry_tips: Tip[];
    google_business_profile: {
        likely_present: boolean;
        headline: string;
        steps: GbpStep[];
    };
}

interface PreviewItem {
    title: string;
    priority: 'high' | 'medium' | 'low';
    category: string | null;
}

const props = defineProps<{
    industries: string[];
    startedAt: string;
}>();

type Phase = 'idle' | 'submitting' | 'processing' | 'teaser' | 'unlocking' | 'revealed' | 'failed';

const phase = ref<Phase>('idle');
const url = ref('');
const industry = ref('');
const company_url = ref(''); // honeypot — must stay empty
const formError = ref<string | null>(null);

const statusUrl = ref('');
const unlockUrl = ref('');

const score = ref<number | null>(null);
const summary = ref<string | null>(null);
const sectionTitles = ref<string[]>([]);
const previewItems = ref<PreviewItem[]>([]);
const lockedCount = ref(0);

const report = ref<Report | null>(null);

const email = ref('');
const emailError = ref<string | null>(null);

const failedMessage = ref<string | null>(null);

const POLL_INTERVAL = 2500;
const MAX_POLLS = 48;
let pollTimer: ReturnType<typeof setTimeout> | null = null;
let pollCount = 0;

onBeforeUnmount(stopPolling);

function csrfToken(): string {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

async function postJson(endpoint: string, data: Record<string, unknown>): Promise<Response> {
    return fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-XSRF-TOKEN': csrfToken(),
        },
        body: JSON.stringify(data),
    });
}

async function submit(): Promise<void> {
    formError.value = null;

    if (!url.value.trim()) {
        formError.value = 'Please enter your website address.';

        return;
    }

    if (!industry.value) {
        formError.value = 'Please choose your industry.';

        return;
    }

    phase.value = 'submitting';

    try {
        const response = await postJson('/seo-assessment', {
            url: url.value,
            industry: industry.value,
            company_url: company_url.value,
            started_at: props.startedAt,
        });

        if (response.status === 422) {
            const body = await response.json();
            const errors = (body.errors ?? {}) as Record<string, string[]>;
            formError.value = Object.values(errors)[0]?.[0] ?? 'Please check your details and try again.';
            phase.value = 'idle';

            return;
        }

        if (response.status === 429) {
            formError.value = 'You have made a few requests already. Please wait a minute and try again.';
            phase.value = 'idle';

            return;
        }

        if (!response.ok) {
            throw new Error('request failed');
        }

        const body = await response.json();
        statusUrl.value = body.statusUrl;
        unlockUrl.value = body.unlockUrl;
        phase.value = 'processing';
        pollCount = 0;
        poll();
    } catch {
        formError.value = 'Something went wrong starting your report. Please try again.';
        phase.value = 'idle';
    }
}

function stopPolling(): void {
    if (pollTimer) {
        clearTimeout(pollTimer);
        pollTimer = null;
    }
}

async function poll(): Promise<void> {
    if (pollCount >= MAX_POLLS) {
        failedMessage.value = 'This is taking longer than expected. Please try again in a few minutes.';
        phase.value = 'failed';

        return;
    }

    pollCount++;

    try {
        const response = await fetch(statusUrl.value, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) {
            throw new Error('status failed');
        }

        const body = await response.json();

        if (body.status === 'failed') {
            failedMessage.value = body.message ?? 'We could not generate a report for that site.';
            phase.value = 'failed';

            return;
        }

        if (body.status === 'completed') {
            applyStatus(body);

            return;
        }

        // pending / processing — keep waiting.
        pollTimer = setTimeout(poll, POLL_INTERVAL);
    } catch {
        pollTimer = setTimeout(poll, POLL_INTERVAL);
    }
}

function applyStatus(body: Record<string, unknown>): void {
    score.value = (body.score as number | null) ?? null;

    if (body.locked === false) {
        report.value = body.report as Report;
        phase.value = 'revealed';

        return;
    }

    summary.value = (body.summary as string | null) ?? null;
    sectionTitles.value = (body.sectionTitles as string[]) ?? [];
    previewItems.value = (body.previewItems as PreviewItem[]) ?? [];
    lockedCount.value = (body.lockedCount as number) ?? 0;
    phase.value = 'teaser';
}

async function unlock(): Promise<void> {
    emailError.value = null;

    if (!email.value.trim()) {
        emailError.value = 'Please enter your email address.';

        return;
    }

    phase.value = 'unlocking';

    try {
        const response = await postJson(unlockUrl.value, {
            email: email.value,
            company_url: company_url.value,
            started_at: props.startedAt,
        });

        if (response.status === 422) {
            const body = await response.json();
            emailError.value = body.errors?.email?.[0] ?? 'Please enter a valid email address.';
            phase.value = 'teaser';

            return;
        }

        if (response.status === 429) {
            emailError.value = 'Too many attempts. Please wait a minute and try again.';
            phase.value = 'teaser';

            return;
        }

        if (!response.ok) {
            throw new Error('unlock failed');
        }

        const body = await response.json();
        applyStatus(body);
    } catch {
        emailError.value = 'Something went wrong unlocking your report. Please try again.';
        phase.value = 'teaser';
    }
}

const scoreColor = computed(() => {
    const value = score.value ?? 0;

    if (value >= 70) {
        return 'var(--color-emerald-600)';
    }

    if (value >= 45) {
        return '#d99a00';
    }

    return 'var(--color-red)';
});

const scoreDashOffset = computed(() => {
    const circumference = 2 * Math.PI * 52;

    return circumference - (circumference * (score.value ?? 0)) / 100;
});

function priorityClasses(priority: string): string {
    switch (priority) {
        case 'high':
            return 'bg-[var(--color-red)]/10 text-[var(--color-red-deep)]';
        case 'low':
            return 'bg-[var(--color-emerald-600)]/10 text-[var(--color-emerald-800)]';
        default:
            return 'bg-amber-500/10 text-amber-700';
    }
}

function categoryLabel(category: string | null): string {
    switch (category) {
        case 'on_page':
            return 'On-page';
        case 'technical':
            return 'Technical';
        case 'content':
            return 'Content';
        case 'local':
            return 'Local';
        case 'trust':
            return 'Trust';
        default:
            return '';
    }
}
</script>

<template>
    <Head>
        <title>Free Instant SEO Report — All American Web Design</title>
        <meta
            name="description"
            content="Get a free, instant AI-powered SEO report for your website. See your score and a prioritized action plan you can start today."
        />
    </Head>

    <!-- Hero -->
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
        <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%)" />
        <img
            src="/american-flag.png"
            alt=""
            class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
        />
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45" />

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-16 sm:pt-44 lg:px-8 lg:pt-52">
            <div class="max-w-2xl">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]" />
                    Free · Instant · AI-powered
                </span>
                <h1 class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl">
                    Your free SEO <span class="text-[var(--color-red)]">report.</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                    Enter your website and industry. We'll scan your site and build a plain-English action plan — what's
                    working, what's not, and exactly what to fix first.
                </p>
            </div>
        </div>

        <div class="h-1.5 w-full bg-[var(--color-red)]" />
    </section>

    <!-- Tool -->
    <section class="bg-[var(--color-cream)] py-16 sm:py-24">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <!-- Honeypot (shared by both forms) -->
            <div aria-hidden="true" class="pointer-events-none absolute -left-[10000px] h-px w-px overflow-hidden opacity-0">
                <label for="company_url">Company URL (leave blank)</label>
                <input id="company_url" v-model="company_url" type="text" name="company_url" tabindex="-1" autocomplete="off" />
            </div>

            <!-- Input form -->
            <form
                v-if="phase === 'idle' || phase === 'submitting'"
                class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8"
                novalidate
                @submit.prevent="submit"
            >
                <div class="grid gap-5">
                    <div>
                        <label for="url" class="block text-sm font-medium text-[var(--color-deep-teal)]">Website address</label>
                        <input
                            id="url"
                            v-model="url"
                            type="url"
                            placeholder="https://yourbusiness.com"
                            autocomplete="url"
                            class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-[var(--color-sand-300)] ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset"
                        />
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-medium text-[var(--color-deep-teal)]">Industry</label>
                        <select
                            id="industry"
                            v-model="industry"
                            class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-[var(--color-sand-300)] ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset"
                        >
                            <option value="" disabled>Choose your industry…</option>
                            <option v-for="option in industries" :key="option" :value="option">{{ option }}</option>
                        </select>
                    </div>
                </div>

                <p v-if="formError" class="mt-4 text-sm text-red-600">{{ formError }}</p>

                <div class="mt-7 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-slate-500">No credit card. No sales pitch. Results in under a minute.</p>
                    <button
                        type="submit"
                        :disabled="phase === 'submitting'"
                        class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow transition hover:bg-[var(--color-red-deep)] disabled:opacity-60 sm:w-auto"
                    >
                        {{ phase === 'submitting' ? 'Starting…' : 'Run my free report' }}
                    </button>
                </div>
            </form>

            <!-- Processing skeleton -->
            <div v-else-if="phase === 'processing'" class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-[var(--color-sand-300)]/60">
                <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-[var(--color-sand-200)] border-t-[var(--color-red)]" />
                <h2 class="mt-6 font-serif text-2xl text-[var(--color-navy-deep)]">Scanning your website…</h2>
                <p class="mt-2 text-sm text-slate-500">We're reading your pages and building your action plan. This usually takes 20–40 seconds.</p>
                <div class="mt-8 space-y-3">
                    <div class="h-4 animate-pulse rounded-full bg-[var(--color-sand-200)]" />
                    <div class="h-4 w-5/6 animate-pulse rounded-full bg-[var(--color-sand-200)]" />
                    <div class="h-4 w-2/3 animate-pulse rounded-full bg-[var(--color-sand-200)]" />
                </div>
            </div>

            <!-- Failed -->
            <div v-else-if="phase === 'failed'" class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-[var(--color-sand-300)]/60">
                <h2 class="font-serif text-2xl text-[var(--color-navy-deep)]">We hit a snag</h2>
                <p class="mt-2 text-sm text-slate-600">{{ failedMessage }}</p>
                <button
                    type="button"
                    class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow transition hover:bg-[var(--color-red-deep)]"
                    @click="phase = 'idle'"
                >
                    Try another site
                </button>
            </div>

            <!-- Teaser / report -->
            <div v-else class="space-y-8">
                <!-- Score + summary -->
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8">
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        <div class="relative h-32 w-32 flex-none">
                            <svg class="h-32 w-32 -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" fill="none" stroke="var(--color-sand-200)" stroke-width="12" />
                                <circle
                                    cx="60"
                                    cy="60"
                                    r="52"
                                    fill="none"
                                    :stroke="scoreColor"
                                    stroke-width="12"
                                    stroke-linecap="round"
                                    :stroke-dasharray="2 * Math.PI * 52"
                                    :stroke-dashoffset="scoreDashOffset"
                                />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-serif text-4xl font-bold text-[var(--color-navy-deep)]">{{ score }}</span>
                                <span class="text-xs tracking-wide text-slate-500 uppercase">/ 100</span>
                            </div>
                        </div>
                        <div class="text-center sm:text-left">
                            <h2 class="font-serif text-2xl text-[var(--color-navy-deep)]">Your SEO score</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                {{ phase === 'revealed' ? report?.summary : summary }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Full report -->
                <template v-if="phase === 'revealed' && report">
                    <div
                        v-for="section in report.sections"
                        :key="section.title"
                        class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8"
                    >
                        <h3 class="font-serif text-xl text-[var(--color-navy-deep)]">{{ section.title }}</h3>
                        <ul class="mt-5 space-y-5">
                            <li v-for="item in section.items" :key="item.title" class="border-l-2 border-[var(--color-sand-300)] pl-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-[var(--color-ink)]">{{ item.title }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" :class="priorityClasses(item.priority)">
                                        {{ item.priority }}
                                    </span>
                                    <span v-if="categoryLabel(item.category)" class="rounded-full bg-[var(--color-sand-100)] px-2 py-0.5 text-xs text-slate-600">
                                        {{ categoryLabel(item.category) }}
                                    </span>
                                </div>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-600">{{ item.detail }}</p>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-3xl bg-[var(--color-emerald-900)] p-6 text-white sm:p-8">
                        <h3 class="font-serif text-xl">{{ report.google_business_profile.headline }}</h3>
                        <ul class="mt-5 space-y-4 text-sm">
                            <li v-for="step in report.google_business_profile.steps" :key="step.title">
                                <p class="font-semibold text-white">{{ step.title }}</p>
                                <p class="mt-0.5 text-white/75">{{ step.detail }}</p>
                            </li>
                        </ul>
                    </div>

                    <div v-if="report.industry_tips.length" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8">
                        <h3 class="font-serif text-xl text-[var(--color-navy-deep)]">Tips for your industry</h3>
                        <ul class="mt-5 space-y-4">
                            <li v-for="tip in report.industry_tips" :key="tip.title">
                                <p class="font-semibold text-[var(--color-ink)]">{{ tip.title }}</p>
                                <p class="mt-0.5 text-sm leading-relaxed text-slate-600">{{ tip.detail }}</p>
                            </li>
                        </ul>
                    </div>
                </template>

                <!-- Locked teaser -->
                <template v-else>
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8">
                        <h3 class="font-serif text-xl text-[var(--color-navy-deep)]">What we found</h3>
                        <ul class="mt-5 space-y-4">
                            <li v-for="item in previewItems" :key="item.title" class="border-l-2 border-[var(--color-sand-300)] pl-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-[var(--color-ink)]">{{ item.title }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" :class="priorityClasses(item.priority)">
                                        {{ item.priority }}
                                    </span>
                                </div>
                            </li>
                        </ul>

                        <!-- Blurred placeholders -->
                        <div class="relative mt-4">
                            <div class="space-y-3 blur-sm select-none" aria-hidden="true">
                                <div v-for="n in Math.min(lockedCount, 4)" :key="n" class="rounded-xl bg-[var(--color-sand-100)] p-4">
                                    <div class="h-3 w-1/2 rounded-full bg-[var(--color-sand-300)]" />
                                    <div class="mt-2 h-3 w-3/4 rounded-full bg-[var(--color-sand-200)]" />
                                </div>
                            </div>

                            <!-- Email gate -->
                            <div class="mt-6 rounded-2xl border border-[var(--color-emerald-600)]/30 bg-[var(--color-emerald-900)] p-6 text-white sm:p-8">
                                <h4 class="font-serif text-2xl">Unlock your full report</h4>
                                <p class="mt-2 text-sm text-white/80">
                                    Enter your email to reveal all {{ lockedCount }} remaining recommendations, your Google
                                    Business Profile checklist, and industry-specific tips.
                                </p>
                                <form class="mt-5 flex flex-col gap-3 sm:flex-row" novalidate @submit.prevent="unlock">
                                    <input
                                        v-model="email"
                                        type="email"
                                        placeholder="you@yourbusiness.com"
                                        autocomplete="email"
                                        class="block w-full rounded-full border-0 bg-white px-5 py-3 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-white/20 ring-inset focus:ring-2 focus:ring-white focus:ring-inset"
                                    />
                                    <button
                                        type="submit"
                                        :disabled="phase === 'unlocking'"
                                        class="inline-flex flex-none items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow transition hover:bg-[var(--color-red-deep)] disabled:opacity-60"
                                    >
                                        {{ phase === 'unlocking' ? 'Unlocking…' : 'Unlock report' }}
                                    </button>
                                </form>
                                <p v-if="emailError" class="mt-3 text-sm text-red-200">{{ emailError }}</p>
                                <p class="mt-3 text-xs text-white/60">We'll never share your email. Unsubscribe anytime.</p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
</template>
