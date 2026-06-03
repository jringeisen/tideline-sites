<?php

use Inertia\Testing\AssertableInertia as Assert;

dataset('locations', [
    'destin' => ['destin', 'Destin'],
    'panama-city-beach' => ['panama-city-beach', 'Panama City Beach'],
    '30a' => ['30a', '30A'],
    'miramar-beach' => ['miramar-beach', 'Miramar Beach'],
    'sandestin' => ['sandestin', 'Sandestin'],
    'fort-walton-beach' => ['fort-walton-beach', 'Fort Walton Beach'],
    'panama-city' => ['panama-city', 'Panama City'],
    'lynn-haven' => ['lynn-haven', 'Lynn Haven'],
    'mexico-beach' => ['mexico-beach', 'Mexico Beach'],
    'seaside' => ['seaside', 'Seaside'],
    'watercolor' => ['watercolor', 'WaterColor'],
    'rosemary-beach' => ['rosemary-beach', 'Rosemary Beach'],
    'alys-beach' => ['alys-beach', 'Alys Beach'],
]);

test('the {slug} location page renders the city name and title', function (string $slug, string $name) {
    $this->get(route('location.show', $slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Location')
            ->where('location.name', $name)
            ->where('meta.title', "{$name} Web Design & SEO — All American Web Design"));
})->with('locations');

test('the {slug} location page emits city-scoped LocalBusiness + Breadcrumb JSON-LD', function (string $slug, string $name) {
    $this->get(route('location.show', $slug))
        ->assertInertia(fn (Assert $page) => $page
            ->where('schema.0.@type', 'ProfessionalService')
            ->where('schema.0.address.addressLocality', $name)
            ->where('schema.1.@type', 'BreadcrumbList'));
})->with('locations');

test('cross-links exist between sibling location pages', function () {
    $nearbySlugs = fn ($nearby) => collect($nearby)->pluck('slug')->filter()->all();

    $this->get(route('location.show', 'destin'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('nearby', fn ($nearby) => in_array('30a', $nearbySlugs($nearby), true)
                && in_array('panama-city-beach', $nearbySlugs($nearby), true)));

    $this->get(route('location.show', 'panama-city-beach'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('nearby', fn ($nearby) => in_array('30a', $nearbySlugs($nearby), true)
                && in_array('destin', $nearbySlugs($nearby), true)));
});

test('an unknown location slug returns 404', function () {
    $this->get('/locations/orlando')->assertNotFound();
});
