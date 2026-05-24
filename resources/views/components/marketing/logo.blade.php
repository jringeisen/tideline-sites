@props(['class' => 'h-8 w-auto'])

{{-- All American Web Design emblem: a heritage shield with a single star
     over three stripes. Monochrome via currentColor so it inherits the
     surrounding text color (cream on navy, navy on cream). --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M20 3 35 8V19C35 28.5 28.7 34.4 20 37.5 11.3 34.4 5 28.5 5 19V8Z"
          stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
    <path d="m20 10.5 1.7 3.7 4 .4-3 2.8.9 4-3.6-2-3.6 2 .9-4-3-2.8 4-.4z"
          fill="currentColor" />
    <path d="M9 24h22M11.5 28h17M15 31.5h10"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" />
</svg>
