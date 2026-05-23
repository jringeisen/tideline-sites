@php
    $selectedPlan = old('plan', $selectedPlan ?? null);

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact Tideline Sites',
        'url' => url()->current(),
        'mainEntity' => [
            '@type' => 'LocalBusiness',
            'name' => 'Tideline Sites',
            'email' => 'hello@tidelinesites.com',
            'areaServed' => 'Emerald Coast, Florida',
        ],
    ];
@endphp

<x-layouts.marketing
    title="Contact Tideline Sites — Web Design & SEO on the Emerald Coast"
    description="Tell us about your business and we'll be in touch within one business day. Serving Destin to Panama City Beach.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero band ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-emerald-900)] text-white">
        <div class="absolute inset-0 -z-10"
             style="background:
                radial-gradient(50% 70% at 80% 0%, rgba(16,185,129,0.30), transparent 60%),
                radial-gradient(50% 60% at 0% 100%, rgba(15,118,110,0.30), transparent 60%),
                linear-gradient(180deg, #0b2a2e 0%, #0d4742 100%);">
        </div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium tracking-wide text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-emerald-200)]"></span>
                    Get in touch
                </span>
                <h1 class="mt-6 font-serif text-5xl leading-[1.05] tracking-tight sm:text-6xl">
                    Let's build something <span class="italic text-[var(--color-sand-200)]">beautiful.</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                    Tell us about your business and which plan you're eyeing. We'll get back to you within one business day.
                </p>
            </div>
        </div>

        <svg class="block w-full text-[var(--color-cream)]" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 40 C 240 80 480 0 720 40 S 1200 80 1440 40 L 1440 80 L 0 80 Z" />
        </svg>
    </section>

    {{-- ───────── Form + info ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12">
                {{-- Form column --}}
                <div class="lg:col-span-7">
                    @if (session('status'))
                        <div role="status"
                             class="mb-8 flex items-start gap-3 rounded-2xl border border-[var(--color-emerald-600)]/25 bg-[var(--color-emerald-50)] px-5 py-4 text-sm text-[var(--color-emerald-800)]">
                            <svg class="mt-0.5 h-5 w-5 flex-none text-[var(--color-emerald-600)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" novalidate
                          class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[var(--color-sand-300)]/60 sm:p-8 dark:bg-white/[0.04] dark:ring-white/10">
                        @csrf

                        {{-- Anti-spam: honeypot + encrypted render timestamp. --}}
                        <div aria-hidden="true" class="pointer-events-none absolute -left-[10000px] h-px w-px overflow-hidden opacity-0">
                            <label for="website">Website (leave blank)</label>
                            <input type="text" name="website" id="website" tabindex="-1" autocomplete="off" value="">
                        </div>
                        <input type="hidden" name="started_at" value="{{ Crypt::encryptString(now()->timestamp) }}">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-medium text-[var(--color-deep-teal)]">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('name') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-[var(--color-deep-teal)]">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('email') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('email')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-[var(--color-deep-teal)]">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required autocomplete="tel"
                                       class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('phone') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                @error('phone')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="plan" class="block text-sm font-medium text-[var(--color-deep-teal)]">Plan you're interested in</label>
                                <div class="relative mt-1.5">
                                    <select name="plan" id="plan"
                                            class="block w-full appearance-none rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 pr-10 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('plan') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]">
                                        <option value="">— Select a plan —</option>
                                        <option value="essential" @selected($selectedPlan === 'essential')>Essential — $299/mo (Web Design + SEO)</option>
                                        <option value="growth" @selected($selectedPlan === 'growth')>Growth — $499/mo (Web Design + SEO + Blogs + Newsletters)</option>
                                        <option value="unsure" @selected($selectedPlan === 'unsure')>Not sure yet — help me decide</option>
                                    </select>
                                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @error('plan')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="message" class="block text-sm font-medium text-[var(--color-deep-teal)]">Message</label>
                                <textarea name="message" id="message" rows="5" required
                                          class="mt-1.5 block w-full rounded-xl border-0 bg-[var(--color-sand-50)] px-4 py-2.5 text-base text-[var(--color-ink)] shadow-sm ring-1 ring-inset @error('message') ring-red-400 @else ring-[var(--color-sand-300)] @enderror focus:bg-white focus:ring-2 focus:ring-inset focus:ring-[var(--color-emerald-600)] dark:focus:bg-white/[0.06]"
                                          placeholder="Tell us a little about your business and what you're hoping to accomplish.">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-7 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                            <p class="text-xs text-slate-500">We'll only use your info to reply. No spam — ever.</p>
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-emerald-600)] px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-[var(--color-emerald-700)] sm:w-auto">
                                Send message
                                <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Info column --}}
                <aside class="lg:col-span-5">
                    <div class="rounded-3xl bg-[var(--color-emerald-900)] p-8 text-white">
                        <h2 class="font-serif text-2xl">Prefer to reach out directly?</h2>
                        <p class="mt-2 text-sm text-white/75">We're a small team, so you'll always talk to the person doing the work.</p>

                        <dl class="mt-8 space-y-6 text-sm">
                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M2 4.5A2.5 2.5 0 014.5 2h2.879A2.5 2.5 0 019.9 3.464l.708 1.768a2.5 2.5 0 01-.547 2.69l-1.06 1.06a11.04 11.04 0 005.018 5.018l1.06-1.06a2.5 2.5 0 012.69-.547l1.768.708A2.5 2.5 0 0118 15.622V18.5a2.5 2.5 0 01-2.5 2.5h-.5C8.073 21 0 12.927 0 4.5V4a2 2 0 011-1.732V4.5z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div>
                                    <dt class="text-white/60">Phone</dt>
                                    <dd class="mt-0.5"><a href="tel:+18506848924" class="font-medium text-white hover:underline">(850) 684-8924</a></dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M2.5 4A1.5 1.5 0 001 5.5v9A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0017.5 4h-15zM3 6.18l6.4 4.27a1.5 1.5 0 001.66 0L17.5 6.18V14.5H3V6.18zM16.1 5.5L10 9.57 3.9 5.5h12.2z" />
                                    </svg>
                                </span>
                                <div>
                                    <dt class="text-white/60">Email</dt>
                                    <dd class="mt-0.5"><a href="mailto:hello@tidelinesites.com" class="font-medium text-white hover:underline">hello@tidelinesites.com</a></dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .2.08.39.22.53l3 3a.75.75 0 101.06-1.06l-2.78-2.78V5z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div>
                                    <dt class="text-white/60">Hours</dt>
                                    <dd class="mt-0.5 font-medium text-white">Mon – Fri · 9am – 5pm CT</dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9.69 18.933A8.501 8.501 0 0118 10a8.5 8.5 0 10-8.31 8.933zM10 11.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div>
                                    <dt class="text-white/60">Service area</dt>
                                    <dd class="mt-0.5 font-medium text-white">Destin → Panama City Beach</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </aside>
            </div>
        </div>
    </section>

</x-layouts.marketing>
