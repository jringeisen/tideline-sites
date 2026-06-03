<?php

use Inertia\Testing\AssertableInertia as Assert;

test('service area page renders successfully', function () {
    $this->get(route('service-area'))->assertOk();
});

test('service area page renders the ServiceArea Inertia component with SEO meta', function () {
    $this->get(route('service-area'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('ServiceArea')
            ->where('meta.title', 'Service Area — All American Web Design | Custom Websites Nationwide')
            ->where('meta.description', fn (string $d) => str_contains($d, 'Destin to Panama City Beach')));
});

test('service area page lists every Gulf Coast town we serve', function () {
    $this->get(route('service-area'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('cities', fn ($cities) => collect([
                'Destin', 'Miramar Beach', 'Sandestin', 'Santa Rosa Beach',
                'Grayton Beach', 'WaterColor', 'Seaside', 'Seagrove Beach',
                'Rosemary Beach', 'Inlet Beach', 'Panama City Beach', 'Panama City',
            ])->every(fn (string $city) => collect($cities)->contains($city))));
});

test('service area page exposes the featured location pages', function () {
    $this->get(route('service-area'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('featuredLocations', 3)
            ->where('featuredLocations.0.slug', 'destin')
            ->where('featuredLocations.1.slug', '30a')
            ->where('featuredLocations.2.slug', 'panama-city-beach'));
});

test('service area page emits Service JSON-LD with areaServed', function () {
    $this->get(route('service-area'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('schema.0.@type', 'Service')
            ->has('schema.0.areaServed'));
});
