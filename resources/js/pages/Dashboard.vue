<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import postsRoutes from '@/routes/admin/posts';
import { edit as editProfile } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isAdmin = computed(() => user.value?.is_admin === true);
const company = computed(() => page.props.company);
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
        <header>
            <p
                class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
            >
                {{ company.name }}
            </p>
            <h1
                class="mt-2 font-serif text-3xl leading-tight tracking-tight text-[var(--color-deep-teal)] sm:text-4xl"
            >
                Welcome back<span v-if="user?.name"
                    >, {{ user.name.split(' ')[0] }}</span
                >.
            </h1>
            <p
                class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-white/70"
            >
                Manage your account, write new posts, and keep your site moving.
            </p>
        </header>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            <article
                v-if="isAdmin"
                class="rounded-3xl bg-white p-7 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
            >
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                >
                    Blog
                </p>
                <h2
                    class="mt-2 font-serif text-2xl text-[var(--color-deep-teal)]"
                >
                    Write something new.
                </h2>
                <p
                    class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-white/70"
                >
                    Draft a post, schedule it, or publish on the spot.
                    Categories and tags help readers find their way.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Button as-child variant="marketing" size="marketing">
                        <Link :href="postsRoutes.create().url">New post</Link>
                    </Button>
                    <Button as-child variant="ghost">
                        <Link :href="postsRoutes.index().url">All posts</Link>
                    </Button>
                </div>
            </article>

            <article
                class="rounded-3xl bg-white p-7 shadow-[0_1px_0_rgba(11,42,46,0.04)] ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10"
            >
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-[var(--color-emerald-700)] uppercase"
                >
                    Account
                </p>
                <h2
                    class="mt-2 font-serif text-2xl text-[var(--color-deep-teal)]"
                >
                    Profile &amp; security.
                </h2>
                <p
                    class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-white/70"
                >
                    Update your name, change your password, manage two-factor
                    authentication and passkeys.
                </p>
                <div class="mt-6">
                    <Button as-child variant="ghost">
                        <Link :href="editProfile()">Open settings</Link>
                    </Button>
                </div>
            </article>
        </div>
    </div>
</template>
