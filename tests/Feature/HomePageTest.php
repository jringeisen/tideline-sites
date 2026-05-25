<?php

test('home page renders successfully', function () {
    $this->get(route('home'))->assertOk();
});

test('home page exposes the brand and primary headline', function () {
    $this->get(route('home'))
        ->assertSee('All American Web Design', false)
        ->assertSee('Built in America.', false)
        ->assertSee('Not outsourced.', false)
        ->assertSee('Veteran-owned', false)
        ->assertDontSee('Tideline', false)
        ->assertDontSee('Emerald Coast', false);
});

test('home page advertises both pricing tiers', function () {
    $this->get(route('home'))
        ->assertSee('$299', false)
        ->assertSee('$499', false)
        ->assertSee('Essential', false)
        ->assertSee('Growth', false);
});

test('home page shows the veteran discount banner', function () {
    $this->get(route('home'))
        ->assertSee('Veterans save 20% on every plan', false);
});

test('home page offers a veteran pricing toggle with discounted prices for every plan', function () {
    $this->get(route('home'))
        ->assertSee('data-veteran-toggle', false)
        ->assertSee('Veteran pricing', false)
        // Discounted (rounded) prices for all three plans.
        ->assertSee('$239', false)
        ->assertSee('$399', false)
        ->assertSee('$800+', false)
        // Regular prices remain in the DOM (toggled client-side).
        ->assertSee('$299', false)
        ->assertSee('$499', false)
        ->assertSee('$1,000+', false);
});

test('home page presents the made-in-America positioning', function () {
    $this->get(route('home'))
        ->assertSee('Built here. For businesses everywhere.', false)
        ->assertSee('100% USA', false)
        ->assertSee('Nationwide', false)
        ->assertSee('Built in the USA', false);
});

test('home page emits SEO meta and LocalBusiness, Service, and FAQ JSON-LD', function () {
    $response = $this->get(route('home'));

    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<link rel="canonical"', false);
    $response->assertSee('application/ld+json', false);
    $response->assertSee('"@type":"ProfessionalService"', false);
    $response->assertSee('"@type":"FAQPage"', false);
    $response->assertSee('"@type":"Service"', false);
    $response->assertSee('"@type":"WebSite"', false);
    $response->assertSee('"areaServed"', false);
    $response->assertSee('"founder"', false);
    $response->assertSee('"knowsAbout"', false);
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
    $response->assertDontSee('Businesses launched', false);
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
        ->assertSee('Businesses launched', false);
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
