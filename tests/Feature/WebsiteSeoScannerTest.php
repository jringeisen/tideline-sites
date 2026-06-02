<?php

use App\Services\WebsiteSeoScanner;
use Illuminate\Support\Facades\Http;

it('extracts on-page seo signals from html', function () {
    $html = <<<'HTML'
    <html lang="en">
    <head>
        <title>Acme Plumbing — Destin, FL</title>
        <meta name="description" content="Fast, friendly plumbing in Destin.">
        <link rel="canonical" href="https://example.com">
        <meta name="viewport" content="width=device-width">
        <meta property="og:title" content="Acme Plumbing">
        <script type="application/ld+json">{"@type":"LocalBusiness","name":"Acme"}</script>
    </head>
    <body>
        <h1>We fix pipes fast</h1>
        <h2>Services</h2>
        <img src="a.jpg" alt="A plumber at work">
        <img src="b.jpg">
        <a href="tel:8501234567">Call us</a>
        <a href="/contact">Contact</a>
        <p>Call us at (850) 123-4567 today.</p>
    </body>
    </html>
    HTML;

    Http::fake([
        'https://example.com/robots.txt' => Http::response("User-agent: *\nSitemap: https://example.com/sitemap.xml", 200),
        'https://example.com/sitemap.xml' => Http::response('<urlset></urlset>', 200),
        'https://example.com' => Http::response($html, 200, ['Content-Type' => 'text/html']),
    ]);

    $signals = app(WebsiteSeoScanner::class)->scan('https://example.com');

    expect($signals['title'])->toBe('Acme Plumbing — Destin, FL')
        ->and($signals['meta_description'])->toBe('Fast, friendly plumbing in Destin.')
        ->and($signals['h1_count'])->toBe(1)
        ->and($signals['image_count'])->toBe(2)
        ->and($signals['images_with_alt'])->toBe(1)
        ->and($signals['image_alt_coverage'])->toBe(0.5)
        ->and($signals['has_schema'])->toBeTrue()
        ->and($signals['schema_types'])->toContain('LocalBusiness')
        ->and($signals['has_tel_link'])->toBeTrue()
        ->and($signals['has_contact_link'])->toBeTrue()
        ->and($signals['phone_detected'])->toBeTrue()
        ->and($signals['has_robots_txt'])->toBeTrue()
        ->and($signals['robots_references_sitemap'])->toBeTrue()
        ->and($signals['lang'])->toBe('en');
});

it('refuses to follow a redirect to a private address', function () {
    Http::fake([
        'https://example.com' => Http::response('', 302, ['Location' => 'http://192.168.0.1/']),
    ]);

    expect(fn () => app(WebsiteSeoScanner::class)->scan('https://example.com'))
        ->toThrow(RuntimeException::class);
});
