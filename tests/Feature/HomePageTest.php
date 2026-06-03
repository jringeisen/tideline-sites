<?php

use Inertia\Testing\AssertableInertia as Assert;

test('home page renders successfully', function () {
    $this->get(route('home'))->assertOk();
});

test('home page renders the Home Inertia component with SEO meta', function () {
    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->where('meta.title', 'All American Web Design — Custom Websites, Built in America')
            ->where('meta.description', fn (string $d) => str_contains($d, 'Built in America')));
});

test('home page exposes the four services and the FAQ list', function () {
    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('services', 4)
            ->where('services.0.name', 'Web Design')
            ->where('services.1.name', 'SEO Optimization')
            ->has('faqs', 6));
});

test('home page emits LocalBusiness, Service, FAQ, and WebSite JSON-LD', function () {
    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('schema.0.@type', 'ProfessionalService')
            ->has('schema.0.areaServed')
            ->has('schema.0.founder')
            ->has('schema.0.knowsAbout')
            ->where('schema.1.@type', 'ItemList')
            ->where('schema.1.itemListElement.0.item.@type', 'Service')
            ->where('schema.2.@type', 'FAQPage')
            ->where('schema.3.@type', 'WebSite'));
});

test('home page hides testimonials and businesses-launched stat by default', function () {
    config()->set('features.testimonials', false);
    config()->set('features.businesses_launched', false);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('features.testimonials', false)
            ->where('features.businessesLaunched', false));
});

test('home page exposes the testimonials flag when enabled', function () {
    config()->set('features.testimonials', true);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page->where('features.testimonials', true));
});

test('home page exposes the businesses-launched flag when enabled', function () {
    config()->set('features.businesses_launched', true);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page->where('features.businessesLaunched', true));
});
