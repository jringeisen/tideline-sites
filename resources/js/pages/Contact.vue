<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { CheckCircle2, Clock, Mail, MapPin, Phone } from 'lucide-vue-next';
import { computed } from 'vue';
import { store } from '@/actions/App/Http/Controllers/ContactController';

const props = defineProps<{
    selectedPlan: string | null;
    isVeteran: boolean;
    startedAt: string;
    status: string | null;
}>();

const page = usePage();
const company = computed(
    () =>
        (page.props.company as {
            phone?: string;
            phoneDisplay?: string;
        }) ?? {},
);

const phoneHref = computed(
    () => `tel:${(company.value.phone ?? '').replace(/-/g, '')}`,
);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    plan: props.selectedPlan ?? '',
    is_veteran: props.isVeteran,
    message: '',
    website: '',
    started_at: props.startedAt,
});

function submit(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () =>
            form.reset(
                'name',
                'email',
                'phone',
                'plan',
                'is_veteran',
                'message',
            ),
    });
}
</script>

<template>
    <div>
        <!-- ───────── Hero band ───────── -->
        <section
            class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white"
        >
            <div
                class="absolute inset-0 -z-10"
                style="
                    background: linear-gradient(
                        180deg,
                        #1e2e44 0%,
                        #243650 60%,
                        #1a2840 100%
                    );
                "
            />
            <img
                src="/american-flag.png"
                alt=""
                width="1729"
                height="910"
                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
                loading="eager"
                fetchpriority="high"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"
            />
            <div
                class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"
            />

            <div
                class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52"
            >
                <div class="max-w-2xl">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-white/90 uppercase backdrop-blur"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"
                        />
                        Get in touch
                    </span>
                    <h1
                        class="mt-6 font-serif text-6xl leading-[0.95] font-bold tracking-tight uppercase sm:text-7xl"
                    >
                        Let's build something
                        <span class="text-[var(--color-red)]"
                            >built to last.</span
                        >
                    </h1>
                    <p
                        class="mt-5 max-w-xl text-lg leading-relaxed text-white/85"
                    >
                        Tell us about your business and which plan you're
                        eyeing. We'll get back to you within one business day,
                        from a real person, not a sales bot.
                    </p>
                </div>
            </div>

            <div class="h-1.5 w-full bg-[var(--color-red)]" />
        </section>

        <!-- ───────── Form + info ───────── -->
        <section class="bg-[var(--color-cream)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-12">
                    <!-- Form column -->
                    <div class="lg:col-span-7">
                        <div
                            v-if="status"
                            role="status"
                            class="mb-8 flex items-start gap-3 rounded-2xl border border-[var(--color-navy)]/20 bg-[var(--color-navy)]/[0.05] px-5 py-4 text-sm text-[var(--color-navy)]"
                        >
                            <CheckCircle2
                                class="mt-0.5 h-5 w-5 flex-none text-[var(--color-red)]"
                                aria-hidden="true"
                            />
                            <p>{{ status }}</p>
                        </div>

                        <form
                            novalidate
                            class="relative rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
                            @submit.prevent="submit"
                        >
                            <!-- Anti-spam: honeypot + encrypted render timestamp. -->
                            <div
                                aria-hidden="true"
                                class="pointer-events-none absolute -left-[10000px] h-px w-px overflow-hidden opacity-0"
                            >
                                <label for="website"
                                    >Website (leave blank)</label
                                >
                                <input
                                    id="website"
                                    v-model="form.website"
                                    type="text"
                                    name="website"
                                    tabindex="-1"
                                    autocomplete="off"
                                />
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-[var(--color-deep-teal)]"
                                        >Name</label
                                    >
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        name="name"
                                        required
                                        autocomplete="name"
                                        class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset dark:focus:bg-white/[0.06]"
                                        :class="
                                            form.errors.name
                                                ? 'ring-red-400'
                                                : 'ring-[var(--color-sand-300)]'
                                        "
                                    />
                                    <p
                                        v-if="form.errors.name"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-[var(--color-deep-teal)]"
                                        >Email</label
                                    >
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        name="email"
                                        required
                                        autocomplete="email"
                                        class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset dark:focus:bg-white/[0.06]"
                                        :class="
                                            form.errors.email
                                                ? 'ring-red-400'
                                                : 'ring-[var(--color-sand-300)]'
                                        "
                                    />
                                    <p
                                        v-if="form.errors.email"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.email }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-[var(--color-deep-teal)]"
                                        >Phone</label
                                    >
                                    <input
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        name="phone"
                                        required
                                        autocomplete="tel"
                                        class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset dark:focus:bg-white/[0.06]"
                                        :class="
                                            form.errors.phone
                                                ? 'ring-red-400'
                                                : 'ring-[var(--color-sand-300)]'
                                        "
                                    />
                                    <p
                                        v-if="form.errors.phone"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.phone }}
                                    </p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="plan"
                                        class="block text-sm font-medium text-[var(--color-deep-teal)]"
                                        >Plan you're interested in</label
                                    >
                                    <div class="relative mt-1.5">
                                        <select
                                            id="plan"
                                            v-model="form.plan"
                                            name="plan"
                                            class="block w-full appearance-none rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 pr-10 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset dark:focus:bg-white/[0.06]"
                                            :class="
                                                form.errors.plan
                                                    ? 'ring-red-400'
                                                    : 'ring-[var(--color-sand-300)]'
                                            "
                                        >
                                            <option value="">
                                                — Select a plan —
                                            </option>
                                            <option value="essential">
                                                Essential — $299/mo (Web Design
                                                + SEO)
                                            </option>
                                            <option value="growth">
                                                Growth — $499/mo (Web Design +
                                                SEO + Blogs + Newsletters)
                                            </option>
                                            <option value="unsure">
                                                Not sure yet, help me decide
                                            </option>
                                        </select>
                                        <svg
                                            class="pointer-events-none absolute top-1/2 right-3 h-4 w-4 -translate-y-1/2 text-slate-500"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <p
                                        v-if="form.errors.plan"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.plan }}
                                    </p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="is_veteran"
                                        class="flex items-start gap-3 rounded-xl bg-[var(--color-sand-50)] px-4 py-3 ring-1 ring-[var(--color-sand-300)] ring-inset dark:bg-white/[0.04] dark:ring-white/10"
                                    >
                                        <input
                                            id="is_veteran"
                                            v-model="form.is_veteran"
                                            type="checkbox"
                                            name="is_veteran"
                                            :value="true"
                                            class="mt-0.5 h-5 w-5 flex-none rounded border-[var(--color-sand-400)] text-[var(--color-red)] focus:ring-[var(--color-red)]"
                                        />
                                        <span
                                            class="text-sm text-[var(--color-deep-teal)] dark:text-white/90"
                                        >
                                            <span class="font-medium"
                                                >I'm a U.S. military
                                                veteran</span
                                            >
                                            <span
                                                class="block text-[var(--color-ink)]/70 dark:text-white/60"
                                                >A 20% veteran discount applies
                                                to your plan.</span
                                            >
                                        </span>
                                    </label>
                                    <p
                                        v-if="form.errors.is_veteran"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.is_veteran }}
                                    </p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="message"
                                        class="block text-sm font-medium text-[var(--color-deep-teal)]"
                                        >Message</label
                                    >
                                    <textarea
                                        id="message"
                                        v-model="form.message"
                                        name="message"
                                        rows="5"
                                        required
                                        class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset focus:bg-white focus:ring-2 focus:ring-[var(--color-emerald-600)] focus:ring-inset dark:focus:bg-white/[0.06]"
                                        :class="
                                            form.errors.message
                                                ? 'ring-red-400'
                                                : 'ring-[var(--color-sand-300)]'
                                        "
                                        placeholder="Tell us a little about your business and what you're hoping to accomplish."
                                    />
                                    <p
                                        v-if="form.errors.message"
                                        class="mt-1.5 text-sm text-red-600"
                                    >
                                        {{ form.errors.message }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="mt-7 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                            >
                                <p class="text-xs text-slate-500">
                                    We'll only use your info to reply. No spam,
                                    ever.
                                </p>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase shadow transition hover:bg-[var(--color-red-deep)] disabled:opacity-60 sm:w-auto"
                                >
                                    Send message
                                    <svg
                                        class="ml-2 h-4 w-4"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Info column -->
                    <aside class="lg:col-span-5">
                        <div
                            class="rounded-3xl bg-[var(--color-emerald-900)] p-8 text-white"
                        >
                            <h2 class="font-serif text-2xl">
                                Prefer to reach out directly?
                            </h2>
                            <p class="mt-2 text-sm text-white/75">
                                We're a small team, so you'll always talk to the
                                person doing the work.
                            </p>

                            <dl class="mt-8 space-y-6 text-sm">
                                <div class="flex items-start gap-4">
                                    <span
                                        class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]"
                                    >
                                        <Phone
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        />
                                    </span>
                                    <div>
                                        <dt class="text-white/60">Phone</dt>
                                        <dd class="mt-0.5">
                                            <a
                                                :href="phoneHref"
                                                class="font-medium text-white hover:underline"
                                                >{{ company.phoneDisplay }}</a
                                            >
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span
                                        class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]"
                                    >
                                        <Mail
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        />
                                    </span>
                                    <div>
                                        <dt class="text-white/60">Email</dt>
                                        <dd
                                            class="mt-0.5 font-medium text-white"
                                        >
                                            Use the form &mdash; we reply within
                                            one business day.
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span
                                        class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]"
                                    >
                                        <Clock
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        />
                                    </span>
                                    <div>
                                        <dt class="text-white/60">Hours</dt>
                                        <dd
                                            class="mt-0.5 font-medium text-white"
                                        >
                                            Mon – Fri · 9am – 5pm CT
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span
                                        class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]"
                                    >
                                        <MapPin
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        />
                                    </span>
                                    <div>
                                        <dt class="text-white/60">
                                            Service area
                                        </dt>
                                        <dd
                                            class="mt-0.5 font-medium text-white"
                                        >
                                            Small businesses nationwide · USA
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
