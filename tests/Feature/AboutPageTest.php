<?php

use Inertia\Testing\AssertableInertia as Assert;

test('the about page renders successfully', function () {
    $this->get(route('about'))->assertOk();
});

test('the about page renders the About Inertia component', function () {
    $this->get(route('about'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('About'));
});

test('the about page exposes SEO meta', function () {
    $this->get(route('about'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('meta.title', 'About All American Web Design — Family-owned & Veteran-owned')
            ->where('meta.description', fn (string $description) => str_contains($description, 'veteran-owned web design')));
});

test('the about page emits Organization + Person + Breadcrumb JSON-LD', function () {
    $this->get(route('about'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('schema', 4)
            ->where('schema.0.@type', 'Organization')
            ->where('schema.1.@type', 'Person')
            ->where('schema.1.name', 'Jon Ringeisen')
            ->where('schema.2.@type', 'Person')
            ->where('schema.2.name', 'Elena Ringeisen')
            ->where('schema.3.@type', 'BreadcrumbList'));
});

test('the home page renders the Home component (which links to the about page)', function () {
    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page->component('Home'));
});
