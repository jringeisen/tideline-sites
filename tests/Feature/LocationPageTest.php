<?php

use App\Models\Location;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceSeeder;

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

beforeEach(function () {
    $this->seed([ServiceSeeder::class, LocationSeeder::class]);
});

test('the {slug} location page renders with the city name in the H1 and title', function (string $slug, string $name) {
    $this->get(route('location.show', $slug))
        ->assertOk()
        ->assertSee("{$name} Web Design", false)
        ->assertSee('<title>', false)
        ->assertSee("{$name} Web Design &amp; SEO", false);
})->with('locations');

test('the {slug} location page emits city-scoped LocalBusiness + Breadcrumb JSON-LD', function (string $slug, string $name) {
    $this->get(route('location.show', $slug))
        ->assertSee('"@type":"ProfessionalService"', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('"addressLocality":"'.$name.'"', false);
})->with('locations');

test('the {slug} location page links to the contact and pricing', function (string $slug) {
    $response = $this->get(route('location.show', $slug));

    $response->assertSee(route('contact.show'), false);
    $response->assertSee('pricing', false);
})->with('locations');

test('location pages carry genuine depth: body content and an FAQ section', function () {
    $this->get(route('location.show', 'destin'))
        ->assertOk()
        ->assertSee('What it takes to rank in Destin', false)
        ->assertSee('"@type":"FAQPage"', false);
});

test('location pages link out to canonical service pages', function () {
    $this->get(route('location.show', 'panama-city-beach'))
        ->assertOk()
        ->assertSee(route('services.show', 'web-design'), false)
        ->assertSee(route('services.index'), false);
});

test('cross-links exist between sibling location pages', function () {
    $this->get(route('location.show', 'destin'))
        ->assertSee(route('location.show', '30a'), false)
        ->assertSee(route('location.show', 'panama-city-beach'), false);

    $this->get(route('location.show', '30a'))
        ->assertSee(route('location.show', 'destin'), false)
        ->assertSee(route('location.show', 'panama-city-beach'), false);

    $this->get(route('location.show', 'panama-city-beach'))
        ->assertSee(route('location.show', 'destin'), false)
        ->assertSee(route('location.show', '30a'), false);
});

test('the location breadcrumb links to the locations hub', function () {
    $this->get(route('location.show', 'destin'))
        ->assertSee(route('locations.index'), false);
});

test('an unknown location slug returns 404', function () {
    $this->get('/locations/orlando')->assertNotFound();
});

test('an unpublished location returns 404', function () {
    $location = Location::factory()->unpublished()->create();

    $this->get(route('location.show', $location->slug))->assertNotFound();
});
