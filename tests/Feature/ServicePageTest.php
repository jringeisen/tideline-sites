<?php

use Inertia\Testing\AssertableInertia as Assert;

dataset('services', [
    'web-design' => ['web-design', 'Web Design'],
    'seo' => ['seo', 'SEO Optimization'],
    'blog-writing' => ['blog-writing', 'Blog Writing'],
    'newsletters' => ['newsletters', 'Newsletters'],
]);

test('the services index renders every offering with ItemList JSON-LD', function () {
    $this->get(route('services.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Services')
            ->has('services', count(config('offerings')))
            ->where('services.0.name', 'Web Design')
            ->where('schema.0.@type', 'ItemList')
            ->where('schema.1.@type', 'BreadcrumbList'));
});

test('the {slug} service page renders and emits Service + FAQ JSON-LD', function (string $slug, string $name) {
    $this->get(route('services.show', $slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Service')
            ->where('service.name', $name)
            ->where('meta.title', "{$name} — All American Web Design")
            ->where('schema.0.@type', 'Service')
            ->where('schema.1.@type', 'BreadcrumbList')
            ->where('schema.2.@type', 'FAQPage'));
})->with('services');

test('an unknown service slug returns 404', function () {
    $this->get('/services/franchising')->assertNotFound();
});
