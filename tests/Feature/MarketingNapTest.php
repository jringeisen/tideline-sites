<?php

use Inertia\Testing\AssertableInertia as Assert;

test('company NAP fields are shared to every Inertia page', function () {
    config()->set('company.phone', '(850) 555-0123');

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('company.phone', '(850) 555-0123')
            ->where('company.locality', 'Panama City Beach')
            ->where('company.region', 'FL'));
});

test('home business schema includes geo coordinates and a telephone when configured', function () {
    config()->set('company.phone', '(850) 555-0123');

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('schema.0.telephone', '(850) 555-0123')
            ->where('schema.0.geo.@type', 'GeoCoordinates')
            ->where('schema.0.geo.latitude', 30.1766));
});

test('home business schema omits telephone when no phone is configured', function () {
    config()->set('company.phone', null);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page->missing('schema.0.telephone'));
});

test('business schema includes sameAs only when social profiles are configured', function () {
    config()->set('company.social', ['https://www.google.com/maps/place/aawd']);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('schema.0.sameAs', ['https://www.google.com/maps/place/aawd']));

    config()->set('company.social', []);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page->missing('schema.0.sameAs'));
});
