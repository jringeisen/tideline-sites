<header class="absolute inset-x-0 top-0 z-30">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center text-white" aria-label="{{ config('company.name') }} home">
            <img src="{{ asset('logo-dark.png') }}" alt="{{ config('company.name') }}" class="h-10 w-auto">
        </a>

        <nav class="hidden items-center gap-9 text-sm font-medium text-white/85 lg:flex" aria-label="Primary">
            <a href="{{ route('home') }}#services" class="transition hover:text-white">Services</a>
            <a href="{{ route('home') }}#pricing" class="transition hover:text-white">Pricing</a>
            <a href="{{ route('service-area') }}" class="transition hover:text-white">Service Area</a>
            <a href="{{ route('about') }}" class="transition hover:text-white">About</a>
            <a href="{{ route('blog.index') }}" class="transition hover:text-white">Blog</a>
            <a href="{{ route('contact.show') }}" class="transition hover:text-white">Contact</a>
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('contact.show') }}"
               class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-[var(--color-emerald-900)] shadow-sm transition hover:bg-[var(--color-sand-200)]">
                Get started
            </a>
        </div>

        {{-- Mobile / tablet menu toggle --}}
        <button type="button"
                data-mobile-menu-toggle
                aria-controls="mobile-menu"
                aria-expanded="false"
                aria-label="Open menu"
                class="inline-flex items-center justify-center rounded-full p-2 text-white ring-1 ring-white/30 transition hover:bg-white/10 lg:hidden">
            <svg data-menu-icon-open class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="4" y1="7" x2="20" y2="7" />
                <line x1="4" y1="12" x2="20" y2="12" />
                <line x1="4" y1="17" x2="20" y2="17" />
            </svg>
            <svg data-menu-icon-close class="hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="6" y1="6" x2="18" y2="18" />
                <line x1="6" y1="18" x2="18" y2="6" />
            </svg>
        </button>
    </div>

    {{-- Mobile / tablet menu panel --}}
    <div id="mobile-menu"
         data-mobile-menu
         class="hidden lg:hidden">
        <div data-mobile-menu-backdrop class="fixed inset-0 z-40 bg-[var(--color-navy-deep,#1e2e44)]/70 backdrop-blur-sm"></div>
        <div class="fixed inset-x-0 top-0 z-50 mx-4 mt-4 rounded-3xl bg-[var(--color-emerald-900)] p-6 text-white shadow-2xl ring-1 ring-white/15">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center text-white" aria-label="{{ config('company.name') }} home">
                    <img src="{{ asset('logo-dark.png') }}" alt="{{ config('company.name') }}" class="h-10 w-auto">
                </a>
                <button type="button"
                        data-mobile-menu-close
                        aria-label="Close menu"
                        class="inline-flex items-center justify-center rounded-full p-2 text-white ring-1 ring-white/30 transition hover:bg-white/10">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="6" y1="6" x2="18" y2="18" />
                        <line x1="6" y1="18" x2="18" y2="6" />
                    </svg>
                </button>
            </div>

            <nav class="mt-8 flex flex-col gap-1 text-base font-medium" aria-label="Mobile">
                <a href="{{ route('home') }}#services" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">Services</a>
                <a href="{{ route('home') }}#pricing" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">Pricing</a>
                <a href="{{ route('service-area') }}" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">Service Area</a>
                <a href="{{ route('about') }}" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">About</a>
                <a href="{{ route('blog.index') }}" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">Blog</a>
                <a href="{{ route('contact.show') }}" class="rounded-xl px-3 py-3 text-white/90 transition hover:bg-white/10 hover:text-white">Contact</a>
            </nav>

            <div class="mt-6 flex flex-col gap-3 border-t border-white/15 pt-6">
                <a href="{{ route('contact.show') }}"
                   class="rounded-full bg-white px-4 py-2.5 text-center text-sm font-semibold text-[var(--color-emerald-900)] shadow-sm transition hover:bg-[var(--color-sand-200)]">
                    Get started
                </a>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const toggle = document.querySelector('[data-mobile-menu-toggle]');
            const panel = document.querySelector('[data-mobile-menu]');
            const backdrop = document.querySelector('[data-mobile-menu-backdrop]');
            const closeBtn = document.querySelector('[data-mobile-menu-close]');
            const openIcon = document.querySelector('[data-menu-icon-open]');
            const closeIcon = document.querySelector('[data-menu-icon-close]');
            if (!toggle || !panel) return;

            const setOpen = (open) => {
                panel.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                openIcon?.classList.toggle('hidden', open);
                closeIcon?.classList.toggle('hidden', !open);
                document.body.style.overflow = open ? 'hidden' : '';
            };

            toggle.addEventListener('click', () => {
                const isOpen = !panel.classList.contains('hidden');
                setOpen(!isOpen);
            });
            backdrop?.addEventListener('click', () => setOpen(false));
            closeBtn?.addEventListener('click', () => setOpen(false));
            panel.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => setOpen(false)));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') setOpen(false);
            });
        })();
    </script>
</header>
