<footer class="bg-[var(--color-emerald-900)] text-white/80">
    <div class="mx-auto max-w-7xl px-6 pt-16 pb-10 lg:px-8">
        <div class="grid gap-10 md:grid-cols-12">
            <div class="md:col-span-5">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-white">
                    <x-marketing.logo class="h-8 w-auto" />
                    <span class="font-serif text-xl tracking-tight">Tideline Sites</span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/70">
                    Web design, SEO, blogs, and newsletters for local businesses on the Emerald Coast — from Destin to Panama City Beach.
                </p>
            </div>

            <div class="md:col-span-3">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-white/60">Company</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#services" class="transition hover:text-white">Services</a></li>
                    <li><a href="{{ route('home') }}#pricing" class="transition hover:text-white">Pricing</a></li>
                    <li><a href="{{ route('about') }}" class="transition hover:text-white">About</a></li>
                    <li><a href="{{ route('home') }}#faq" class="transition hover:text-white">FAQ</a></li>
                    <li><a href="{{ route('contact.show') }}" class="transition hover:text-white">Contact</a></li>
                </ul>
            </div>

            <div class="md:col-span-4">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-white/60">Service Area</h3>
                <ul class="mt-4 flex flex-wrap gap-2 text-sm">
                    <li><a href="{{ route('location.show', 'destin') }}" class="rounded-full bg-white/10 px-3 py-1 text-white/90 transition hover:bg-white/20">Destin</a></li>
                    <li><a href="{{ route('location.show', '30a') }}" class="rounded-full bg-white/10 px-3 py-1 text-white/90 transition hover:bg-white/20">30A</a></li>
                    <li><a href="{{ route('location.show', 'panama-city-beach') }}" class="rounded-full bg-white/10 px-3 py-1 text-white/90 transition hover:bg-white/20">Panama City Beach</a></li>
                </ul>
                <p class="mt-4 text-sm leading-relaxed text-white/60">
                    Also serving Miramar Beach, Sandestin, Santa Rosa Beach, Grayton Beach, Seaside, WaterColor, Seagrove, Seacrest, Alys Beach, Rosemary Beach, Inlet Beach, and Panama City.
                </p>
                <a href="{{ route('service-area') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-white/90 transition hover:text-white">
                    See full service area
                    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-start justify-between gap-4 border-t border-white/10 pt-6 text-xs text-white/60 sm:flex-row sm:items-center">
            <p>&copy; {{ date('Y') }} Tideline Sites. All rights reserved.</p>
            <p>Made on the Emerald Coast.</p>
        </div>
    </div>
</footer>
