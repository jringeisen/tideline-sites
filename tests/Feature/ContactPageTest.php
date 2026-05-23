<?php

use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Crypt;

/**
 * Build a base valid payload, defaulting to "filled out 10 seconds ago" so it
 * clears the time-trap.
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

test('contact page renders the form with all expected fields', function () {
    $this->get(route('contact.show'))
        ->assertOk()
        ->assertSee('Let\'s build something', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="phone"', false)
        ->assertSee('name="plan"', false)
        ->assertSee('name="message"', false)
        ->assertSee('name="website"', false)
        ->assertSee('name="started_at"', false)
        ->assertSee('Essential', false)
        ->assertSee('Growth', false);
});

test('the contact page preselects the plan from the query string', function () {
    $this->get(route('contact.show', ['plan' => 'growth']))
        ->assertOk()
        ->assertSee('value="growth" selected', false);
});

test('submitting valid data persists the inquiry and redirects with a success flash', function () {
    $this->post(route('contact.store'), contactPayload())
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(1);

    $inquiry = ContactInquiry::sole();
    expect($inquiry->name)->toBe('Jane Beachgoer')
        ->and($inquiry->email)->toBe('jane@example.com')
        ->and($inquiry->plan)->toBe('growth');
});

test('submitting without required fields fails validation', function () {
    $this->post(route('contact.store'), [
        'started_at' => Crypt::encryptString(now()->subSeconds(10)->timestamp),
    ])->assertSessionHasErrors(['name', 'email', 'phone', 'message']);

    expect(ContactInquiry::count())->toBe(0);
});

test('an invalid plan value is rejected', function () {
    $this->post(route('contact.store'), contactPayload(['plan' => 'enterprise-mega-deluxe']))
        ->assertSessionHasErrors('plan');
});

test('plan is optional and defaults to null', function () {
    $payload = contactPayload();
    unset($payload['plan']);

    $this->post(route('contact.store'), $payload)
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::sole()->plan)->toBeNull();
});

test('a filled honeypot silently drops the submission with the same success flash', function () {
    $this->post(route('contact.store'), contactPayload(['website' => 'http://spam.example']))
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('a submission faster than the time-trap is silently dropped', function () {
    $this->post(route('contact.store'), contactPayload([
        'started_at' => Crypt::encryptString(now()->timestamp),
    ]))
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('a missing or tampered started_at is treated as spam', function () {
    $this->post(route('contact.store'), contactPayload(['started_at' => 'not-a-valid-token']))
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('the contact form is rate limited per IP', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('contact.store'), contactPayload(['email' => "valid{$i}@example.com"]))
            ->assertRedirect(route('contact.show'));
    }

    $this->post(route('contact.store'), contactPayload(['email' => 'sixth@example.com']))
        ->assertStatus(429);

    expect(ContactInquiry::count())->toBe(5);
});
