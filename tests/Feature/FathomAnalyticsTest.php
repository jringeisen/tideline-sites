<?php

test('fathom script is not included on guest pages in non-production environments', function () {
    expect(app()->environment())->not->toBe('production');

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('cdn.usefathom.com', false);
});

test('fathom script is included on guest pages in production', function () {
    app()->detectEnvironment(fn () => 'production');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('https://cdn.usefathom.com/script.js', false)
        ->assertSee('data-site="WAVFXUWL"', false)
        ->assertSee('data-spa="auto"', false);
});
