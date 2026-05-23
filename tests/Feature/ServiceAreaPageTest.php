<?php

test('service area page renders successfully', function () {
    $this->get(route('service-area'))->assertOk();
});

test('service area page exposes the brand and primary headline', function () {
    $this->get(route('service-area'))
        ->assertSee('Service Area', false)
        ->assertSee('Emerald Coast Web Design', false)
        ->assertSee('Destin to Panama City Beach', false);
});

test('service area page lists every Emerald Coast town we serve', function () {
    $response = $this->get(route('service-area'));

    foreach ([
        'Destin', 'Miramar Beach', 'Sandestin', 'Santa Rosa Beach',
        'Grayton Beach', 'WaterColor', 'Seaside', 'Seagrove Beach',
        'Rosemary Beach', 'Inlet Beach', 'Panama City Beach', 'Panama City',
    ] as $city) {
        $response->assertSee($city, false);
    }
});

test('service area page links to the featured location pages', function () {
    $this->get(route('service-area'))
        ->assertSee(route('location.show', 'destin'), false)
        ->assertSee(route('location.show', '30a'), false)
        ->assertSee(route('location.show', 'panama-city-beach'), false);
});

test('service area page emits SEO meta and Service JSON-LD with areaServed', function () {
    $this->get(route('service-area'))
        ->assertSee('<meta name="description"', false)
        ->assertSee('<link rel="canonical"', false)
        ->assertSee('application/ld+json', false)
        ->assertSee('"@type":"Service"', false)
        ->assertSee('"areaServed"', false);
});
