@php
    $title = $title ?? 'All American Web Design — Custom Websites, Built in America';
    $description = $description ?? 'All American Web Design is a veteran-owned studio building custom, high-converting websites for American small businesses — built in America, not outsourced.';
    $canonical = $canonical ?? url()->current();
    $ogImage = $ogImage ?? asset('og-image.png');
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#faf5e9" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#14213a" media="(prefers-color-scheme: dark)">

    {{-- FOUC-safe dark mode detection (follows OS / app appearance setting) --}}
    <script>
        (() => {
            const getStored = () => { try { return localStorage.getItem('appearance'); } catch (e) { return null; } };
            const apply = () => {
                const stored = getStored();
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = stored === 'dark' || ((!stored || stored === 'system') && prefersDark);
                document.documentElement.classList.toggle('dark', isDark);
            };
            apply();
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', apply);
        })();
    </script>

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="All American Web Design">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="/favicons/favicon.ico" sizes="any">
    <link rel="icon" href="/favicons/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicons/favicon-32x32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="/favicons/favicon-16x16.png" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" href="/favicons/apple-touch-icon.png">

    @fonts

    @vite(['resources/css/app.css'])

    @production
        {{-- Fathom - beautiful, simple website analytics --}}
        <script src="https://cdn.usefathom.com/script.js" data-site="XFYXDHZS" defer></script>
        {{-- / Fathom --}}
    @endproduction

    @stack('schema')
</head>
<body class="font-body antialiased bg-[var(--color-cream)] text-[var(--color-ink)]">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg">
        Skip to content
    </a>

    <x-marketing.header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-marketing.footer />
</body>
</html>
