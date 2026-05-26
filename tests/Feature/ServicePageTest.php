<?php

use App\Models\Location;
use App\Models\Service;

test('the services index lists published services', function () {
    $published = Service::factory()->create(['name' => 'Web Design']);
    $hidden = Service::factory()->unpublished()->create(['name' => 'Secret Service']);

    $this->get(route('services.index'))
        ->assertOk()
        ->assertSee('Web Design', false)
        ->assertSee(route('services.show', $published->slug), false)
        ->assertDontSee('Secret Service', false);
});

test('a service page renders with its name and SEO schema', function () {
    $service = Service::factory()->create([
        'name' => 'SEO Optimization',
        'summary' => 'Local SEO that wins.',
        'body' => '<p>'.str_repeat('word ', 120).'</p>',
    ]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee('SEO Optimization', false)
        ->assertSee('"@type":"Service"', false)
        ->assertSee('"@type":"BreadcrumbList"', false);
});

test('a service page emits FAQPage schema when it has faqs', function () {
    $service = Service::factory()->create([
        'faqs' => [['question' => 'Do you do local SEO?', 'answer' => 'Yes, every plan.']],
    ]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('Do you do local SEO?', false);
});

test('a service page links to the towns it serves', function () {
    $service = Service::factory()->create();
    $location = Location::factory()->create(['name' => 'Destin']);
    $service->locations()->sync([$location->id => ['sort_order' => 0]]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee(route('location.show', $location->slug), false)
        ->assertSee('Destin', false);
});

test('an unpublished service returns 404', function () {
    $service = Service::factory()->unpublished()->create();

    $this->get(route('services.show', $service->slug))->assertNotFound();
});

test('an unknown service slug returns 404', function () {
    $this->get('/services/nope')->assertNotFound();
});

test('the locations index lists published locations grouped by region', function () {
    Location::factory()->create(['name' => 'Destin', 'region' => 'Emerald Coast']);
    Location::factory()->unpublished()->create(['name' => 'Hidden Town']);

    $this->get(route('locations.index'))
        ->assertOk()
        ->assertSee('Destin', false)
        ->assertSee('Emerald Coast', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertDontSee('Hidden Town', false);
});
