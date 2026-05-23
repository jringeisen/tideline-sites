@php
    $title = $title ?? 'Tideline Sites — Web Design & SEO on the Emerald Coast';
    $description = $description ?? 'Tideline Sites builds beautiful, high-converting websites and runs SEO, blogs, and newsletters for local businesses from Destin to Panama City Beach.';
    $canonical = $canonical ?? url()->current();
    $ogImage = $ogImage ?? asset('og-image.jpg');
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f766e" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0b2a2e" media="(prefers-color-scheme: dark)">

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
    <meta property="og:site_name" content="Tideline Sites">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts

    @vite(['resources/css/app.css'])

    @stack('schema')
</head>
<body class="font-sans antialiased bg-[var(--color-cream)] text-[var(--color-ink)]">
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
