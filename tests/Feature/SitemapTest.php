<?php

test('the sitemap is served as XML and includes home, contact, and all locations', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $response->assertSee('<urlset', false);

    foreach ([
        route('home'),
        route('about'),
        route('service-area'),
        route('contact.show'),
        route('location.show', 'destin'),
        route('location.show', 'panama-city-beach'),
        route('location.show', '30a'),
    ] as $url) {
        $response->assertSee("<loc>{$url}</loc>", false);
    }
});

test('the sitemap is valid XML', function () {
    $body = $this->get('/sitemap.xml')->getContent();

    $previousErrors = libxml_use_internal_errors(true);
    $doc = simplexml_load_string($body);
    libxml_use_internal_errors($previousErrors);

    expect($doc)->not->toBeFalse();
});

test('robots.txt advertises the sitemap', function () {
    $contents = file_get_contents(public_path('robots.txt'));

    expect($contents)->toContain('Sitemap:');
});
