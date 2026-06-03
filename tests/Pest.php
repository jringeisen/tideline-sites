<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * A valid encrypted anti-spam "started_at" timestamp, aged past the time trap.
 */
function validStartedAt(int $secondsAgo = 10): string
{
    return Crypt::encryptString(
        (string) (now()->timestamp - $secondsAgo),
    );
}

/**
 * Build a base valid contact payload, defaulting to "filled out 10 seconds ago"
 * so it clears the time-trap.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function contactPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane Beachgoer',
        'email' => 'jane@example.com',
        'phone' => '850-555-0199',
        'plan' => 'growth',
        'message' => 'I run a vacation rental in Seaside and need a new website plus monthly content.',
        'website' => '',
        'started_at' => Crypt::encryptString(now()->subSeconds(10)->timestamp),
    ], $overrides);
}
