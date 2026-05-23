<?php

test('home page renders successfully', function () {
    $this->get(route('home'))->assertOk();
});

test('home page exposes the brand and primary headline', function () {
    $this->get(route('home'))
        ->assertSee('Tideline Sites', false)
        ->assertSee('Emerald Coast', false)
        ->assertSee('Web Design &amp; SEO', false)
        ->assertSee('Websites that win', false);
});

test('home page advertises both pricing tiers', function () {
    $this->get(route('home'))
        ->assertSee('$299', false)
        ->assertSee('$499', false)
        ->assertSee('Essential', false)
        ->assertSee('Growth', false);
});

test('home page lists key Emerald Coast service areas', function () {
    $response = $this->get(route('home'));

    foreach (['Destin', 'Santa Rosa Beach', 'Seaside', 'Rosemary Beach', 'Panama City Beach'] as $city) {
        $response->assertSee($city, false);
    }
});

test('home page emits SEO meta and LocalBusiness, Service, and FAQ JSON-LD', function () {
    $response = $this->get(route('home'));

    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<link rel="canonical"', false);
    $response->assertSee('application/ld+json', false);
    $response->assertSee('"@type":"ProfessionalService"', false);
    $response->assertSee('"@type":"FAQPage"', false);
    $response->assertSee('"@type":"Service"', false);
    $response->assertSee('"areaServed"', false);
});

test('home page links the pricing CTAs to the contact route with a plan', function () {
    $this->get(route('home'))
        ->assertSee('/contact?plan=essential', false)
        ->assertSee('/contact?plan=growth', false);
});

test('home page advertises a 1-2 week average launch', function () {
    $this->get(route('home'))
        ->assertSee('1&ndash;2 weeks', false)
        ->assertSee('one to two weeks', false);
});

test('home page hides testimonials and businesses-launched stat by default', function () {
    config()->set('features.testimonials', false);
    config()->set('features.businesses_launched', false);

    $response = $this->get(route('home'));

    $response->assertDontSee('What local owners are saying', false);
    $response->assertDontSee('Real businesses. Real results.', false);
    $response->assertDontSee('Local businesses launched', false);
});

test('home page shows testimonials when the feature flag is enabled', function () {
    config()->set('features.testimonials', true);

    $this->get(route('home'))
        ->assertSee('Real businesses. Real results.', false)
        ->assertSee('Sandcastle Vacation Rentals', false);
});

test('home page shows the businesses-launched stat when the feature flag is enabled', function () {
    config()->set('features.businesses_launched', true);

    $this->get(route('home'))
        ->assertSee('Local businesses launched', false);
});

test('home page showcases recent project work with outbound links', function () {
    $this->get(route('home'))
        ->assertSee('Recent work', false)
        ->assertSee('Venture', false)
        ->assertSee('Wordsmith', false)
        ->assertSee('https://learnwithventure.com', false)
        ->assertSee('https://usewordsmith.com', false)
        ->assertSee('rel="noopener"', false);
});
