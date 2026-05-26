<?php

use App\Models\Location;
use App\Models\Service;

it('auto-generates slugs from name for locations and services', function () {
    expect(Location::factory()->create(['name' => 'Panama City Beach', 'slug' => null])->slug)
        ->toBe('panama-city-beach');
    expect(Service::factory()->create(['name' => 'Web Design', 'slug' => null])->slug)
        ->toBe('web-design');
});

it('casts json and boolean columns', function () {
    $location = Location::factory()->create([
        'segments' => [['title' => 'Charters', 'body' => 'Boats and trips.']],
        'faqs' => [['question' => 'How much?', 'answer' => 'From $299.']],
        'lat' => 30.1766,
    ]);

    expect($location->segments)->toBeArray()
        ->and($location->segments[0]['title'])->toBe('Charters')
        ->and($location->faqs[0]['question'])->toBe('How much?')
        ->and($location->lat)->toBeFloat()
        ->and($location->is_published)->toBeTrue();
});

it('scopes published records', function () {
    Service::factory()->count(2)->create();
    Service::factory()->unpublished()->create();

    expect(Service::published()->count())->toBe(2);
});

it('links locations to services and orders by pivot', function () {
    $location = Location::factory()->create();
    $a = Service::factory()->create(['name' => 'Web Design']);
    $b = Service::factory()->create(['name' => 'SEO']);

    $location->services()->sync([
        $b->id => ['sort_order' => 0],
        $a->id => ['sort_order' => 1],
    ]);

    expect($location->load('services')->services->pluck('id')->all())->toBe([$b->id, $a->id])
        ->and($a->load('locations')->locations->pluck('id')->all())->toBe([$location->id]);
});

it('cross-links nearby locations', function () {
    $destin = Location::factory()->create(['name' => 'Destin']);
    $thirtyA = Location::factory()->create(['name' => '30A']);

    $destin->nearby()->sync([$thirtyA->id => ['sort_order' => 0]]);

    expect($destin->load('nearby')->nearby->pluck('id')->all())->toBe([$thirtyA->id]);
});
