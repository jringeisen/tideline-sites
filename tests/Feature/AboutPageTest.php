<?php

test('the about page renders successfully', function () {
    $this->get(route('about'))->assertOk();
});

test('the about page identifies the team and ownership story', function () {
    $this->get(route('about'))
        ->assertSee('Jon Ringeisen', false)
        ->assertSee('Elena Ringeisen', false)
        ->assertSee('Family-owned', false)
        ->assertSee('Veteran-owned', false)
        ->assertSee('husband-and-wife', false);
});

test('the about page tells the veteran story with specifics', function () {
    $this->get(route('about'))
        ->assertSee('U.S. Army', false)
        ->assertSee('Sergeant', false)
        ->assertSee('Mosul', false)
        ->assertSee('Kuwait', false);
});

test('the about page emits Organization + Person + Breadcrumb JSON-LD', function () {
    $response = $this->get(route('about'));

    $response->assertSee('"@type":"Organization"', false);
    $response->assertSee('"@type":"Person"', false);
    $response->assertSee('"@type":"BreadcrumbList"', false);
    $response->assertSee('"name":"Jon Ringeisen"', false);
    $response->assertSee('"name":"Elena Ringeisen"', false);
});

test('the about page includes the team photo', function () {
    $this->get(route('about'))
        ->assertSee('team/jon-elena.jpeg', false);
});

test('the home page teaser links to the about page', function () {
    $this->get(route('home'))
        ->assertSee(route('about'), false)
        ->assertSee('Meet the team', false);
});
