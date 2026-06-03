<?php

use function Pest\Laravel\get;

$expectedTags = [
    '<link rel="icon" href="/favicons/favicon-v2.ico" sizes="any">',
    '<link rel="icon" href="/favicons/favicon-v2.svg" type="image/svg+xml">',
    '<link rel="icon" href="/favicons/favicon-v2-32x32.png" type="image/png" sizes="32x32">',
    '<link rel="icon" href="/favicons/favicon-v2-16x16.png" type="image/png" sizes="16x16">',
    '<link rel="apple-touch-icon" href="/favicons/apple-touch-icon-v2.png">',
];

test('marketing layout links the brand favicons', function () use ($expectedTags) {
    $response = get(route('home'))->assertOk();

    foreach ($expectedTags as $tag) {
        $response->assertSee($tag, false);
    }
});

test('inertia app layout links the brand favicons', function () use ($expectedTags) {
    $response = get(route('login'))->assertOk();

    foreach ($expectedTags as $tag) {
        $response->assertSee($tag, false);
    }
});

test('referenced favicon files exist in public/favicons', function () {
    foreach ([
        'favicon-v2.ico',
        'favicon-v2.svg',
        'favicon-v2-32x32.png',
        'favicon-v2-16x16.png',
        'apple-touch-icon-v2.png',
    ] as $file) {
        expect(public_path("favicons/{$file}"))->toBeFile();
    }
});
