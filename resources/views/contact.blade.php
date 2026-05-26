@php
    $selectedPlan = old('plan', $selectedPlan ?? null);
    $isVeteran = (bool) old('is_veteran', $isVeteran ?? false);

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact All American Web Design',
        'url' => url()->current(),
        'mainEntity' => [
            '@type' => 'LocalBusiness',
            'name' => config('company.name'),
            'email' => config('company.email'),
            'areaServed' => 'United States',
        ],
    ];
@endphp

<x-layouts.marketing
    title="Contact All American Web Design — Custom Websites, Built in America"
    description="Tell us about your business and we'll be in touch within one business day. Veteran-owned, American-made web design for small businesses nationwide.">

    @push('schema')
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    {{-- ───────── Hero band ───────── --}}
    <section class="relative isolate overflow-hidden bg-[var(--color-navy-deep)] text-white">
        {{-- Base navy field --}}
        <div class="absolute inset-0 -z-10" style="background: linear-gradient(180deg, #1e2e44 0%, #243650 60%, #1a2840 100%);"></div>
        {{-- American flag, muted into the navy via luminosity blend --}}
        <img
            src="{{ asset('american-flag.png') }}"
            alt=""
            width="1729" height="910"
            class="absolute inset-0 -z-10 h-full w-full object-cover opacity-20 mix-blend-luminosity"
            loading="eager"
            fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[var(--color-navy-deep)]/95 via-[var(--color-navy-deep)]/75 to-[var(--color-navy-deep)]/45"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--color-navy-deep)]/50 via-transparent to-[var(--color-navy-deep)]/90"></div>

        <div class="mx-auto max-w-7xl px-6 pt-36 pb-20 sm:pt-44 lg:px-8 lg:pt-52">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white/90 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-red)]"></span>
                    Get in touch
                </span>
                <h1 class="mt-6 font-serif text-6xl font-bold uppercase leading-[0.95] tracking-tight sm:text-7xl">
                    Let's build something <span class="text-[var(--color-red)]">built to last.</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                    Tell us about your business and which plan you're eyeing. We'll get back to you within one business day, from a real person, not a sales bot.
                </p>
            </div>
        </div>

        {{-- Heritage stripe divider --}}
        <div class="h-1.5 w-full bg-[var(--color-red)]"></div>
    </section>

    {{-- ───────── Form + info ───────── --}}
    <section class="bg-[var(--color-cream)] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12">
                {{-- Form column --}}
                <div class="lg:col-span-7">
                    @if (session('status'))
                        <div role="status"
                             class="mb-8 flex items-start gap-3 rounded-2xl border border-[var(--color-navy)]/20 bg-[var(--color-navy)]/[0.05] px-5 py-4 text-sm text-[var(--color-navy)]">
                            <svg class="mt-0.5 h-5 w-5 flex-none text-[var(--color-red)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                                        <option value="unsure" @selected($selectedPlan === 'unsure')>Not sure yet, help me decide</option>
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
                                <label for="is_veteran" class="flex items-start gap-3 rounded-xl bg-[var(--color-sand-50)] px-4 py-3 ring-1 ring-inset ring-[var(--color-sand-300)] dark:bg-white/[0.04] dark:ring-white/10">
                                    <input type="checkbox" name="is_veteran" id="is_veteran" value="1" @checked($isVeteran)
                                           class="mt-0.5 h-5 w-5 flex-none rounded border-[var(--color-sand-400)] text-[var(--color-red)] focus:ring-[var(--color-red)]">
                                    <span class="text-sm text-[var(--color-deep-teal)] dark:text-white/90">
                                        <span class="font-medium">I'm a U.S. military veteran</span>
                                        <span class="block text-[var(--color-ink)]/70 dark:text-white/60">A 20% veteran discount applies to your plan.</span>
                                    </span>
                                </label>
                                @error('is_veteran')
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
                            <p class="text-xs text-slate-500">We'll only use your info to reply. No spam, ever.</p>
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-[var(--color-red)] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow transition hover:bg-[var(--color-red-deep)] sm:w-auto">
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
                                    <x-heroicon-m-phone class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <div>
                                    <dt class="text-white/60">Phone</dt>
                                    <dd class="mt-0.5"><a href="tel:{{ str_replace('-', '', config('company.phone')) }}" class="font-medium text-white hover:underline">{{ config('company.phone_display') }}</a></dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <x-heroicon-m-envelope class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <div>
                                    <dt class="text-white/60">Email</dt>
                                    <dd class="mt-0.5"><a href="mailto:{{ config('company.email') }}" class="font-medium text-white hover:underline">{{ config('company.email') }}</a></dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <x-heroicon-m-clock class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <div>
                                    <dt class="text-white/60">Hours</dt>
                                    <dd class="mt-0.5 font-medium text-white">Mon – Fri · 9am – 5pm CT</dd>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-white/10 text-[var(--color-emerald-200)]">
                                    <x-heroicon-m-map-pin class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <div>
                                    <dt class="text-white/60">Service area</dt>
                                    <dd class="mt-0.5 font-medium text-white">Small businesses nationwide · USA</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </aside>
            </div>
        </div>
    </section>

</x-layouts.marketing>
