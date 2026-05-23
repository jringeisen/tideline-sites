@once
    @push('schema')
        <style>
            [data-coastline] .coast-path {
                stroke-dasharray: 1;
                stroke-dashoffset: 1;
            }
            [data-coastline] .coast-dot { r: 0; }
            [data-coastline] .coast-label { opacity: 0; }

            [data-coastline].is-visible .coast-path {
                animation: coast-draw 1.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
            [data-coastline].is-visible .coast-dot {
                animation: coast-dot 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }
            [data-coastline].is-visible .coast-label {
                animation: coast-label 0.45s ease-out forwards;
            }

            [data-coastline].is-visible .coast-dot[data-i="0"] { animation-delay: 0.35s; }
            [data-coastline].is-visible .coast-dot[data-i="1"] { animation-delay: 0.60s; }
            [data-coastline].is-visible .coast-dot[data-i="2"] { animation-delay: 0.85s; }
            [data-coastline].is-visible .coast-dot[data-i="3"] { animation-delay: 1.10s; }
            [data-coastline].is-visible .coast-dot[data-i="4"] { animation-delay: 1.35s; }

            [data-coastline].is-visible .coast-label[data-i="0"] { animation-delay: 0.55s; }
            [data-coastline].is-visible .coast-label[data-i="1"] { animation-delay: 0.80s; }
            [data-coastline].is-visible .coast-label[data-i="2"] { animation-delay: 1.05s; }
            [data-coastline].is-visible .coast-label[data-i="3"] { animation-delay: 1.30s; }
            [data-coastline].is-visible .coast-label[data-i="4"] { animation-delay: 1.55s; }

            @keyframes coast-draw {
                to { stroke-dashoffset: 0; }
            }
            @keyframes coast-dot {
                from { r: 0; }
                60%  { r: 5.5; }
                to   { r: 4; }
            }
            @keyframes coast-label {
                from { opacity: 0; transform: translateY(2px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            @media (prefers-reduced-motion: reduce) {
                [data-coastline] .coast-path { stroke-dasharray: none; stroke-dashoffset: 0; }
                [data-coastline] .coast-dot { r: 4; }
                [data-coastline] .coast-label { opacity: 1; }
                [data-coastline].is-visible .coast-path,
                [data-coastline].is-visible .coast-dot,
                [data-coastline].is-visible .coast-label { animation: none; }
            }
        </style>
    @endpush
@endonce

<svg viewBox="0 0 600 120" class="{{ $attributes->get('class', 'h-24 w-full text-[var(--color-emerald-600)]') }}" data-coastline aria-hidden="true">
    <path class="coast-path"
          pathLength="1"
          d="M 10 88 Q 25 82 40 78 Q 110 70 180 76 Q 240 52 300 60 Q 360 44 420 54 Q 480 78 540 68 Q 570 62 590 60"
          stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
    <g fill="currentColor">
        <circle class="coast-dot" data-i="0" cx="40" cy="78" r="4" />
        <circle class="coast-dot" data-i="1" cx="180" cy="76" r="4" />
        <circle class="coast-dot" data-i="2" cx="300" cy="60" r="4" />
        <circle class="coast-dot" data-i="3" cx="420" cy="54" r="4" />
        <circle class="coast-dot" data-i="4" cx="540" cy="68" r="4" />
    </g>
    <g class="font-sans fill-[#0b2a2e] dark:fill-white/85" font-size="10" text-anchor="middle">
        <text class="coast-label" data-i="0" x="40"  y="100">Destin</text>
        <text class="coast-label" data-i="1" x="180" y="98">Sandestin</text>
        <text class="coast-label" data-i="2" x="300" y="40">30A</text>
        <text class="coast-label" data-i="3" x="420" y="34">Rosemary</text>
        <text class="coast-label" data-i="4" x="540" y="90">PCB</text>
    </g>
</svg>

@once
    @push('schema')
        <script>
            (() => {
                const init = () => {
                    const els = document.querySelectorAll('[data-coastline]:not([data-coastline-bound])');
                    if (!els.length) return;
                    els.forEach((el) => el.setAttribute('data-coastline-bound', ''));

                    if (!('IntersectionObserver' in window)) {
                        els.forEach((el) => el.classList.add('is-visible'));
                        return;
                    }

                    const io = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                io.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.25 });

                    els.forEach((el) => io.observe(el));
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();
        </script>
    @endpush
@endonce
