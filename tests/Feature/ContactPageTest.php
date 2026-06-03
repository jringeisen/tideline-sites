<?php

use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Crypt;
use Inertia\Testing\AssertableInertia as Assert;

test('contact page renders the Contact Inertia component with form scaffolding', function () {
    $this->get(route('contact.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Contact')
            ->has('startedAt')
            ->where('schema.0.@type', 'ContactPage'));
});

test('the contact page preselects the plan from the query string', function () {
    $this->get(route('contact.show', ['plan' => 'growth']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('selectedPlan', 'growth'));
});

test('the contact page reports the veteran flag as false by default', function () {
    $this->get(route('contact.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('isVeteran', false));
});

test('the contact page reports the veteran flag from the query string', function () {
    $this->get(route('contact.show', ['veteran' => 1]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('isVeteran', true));
});

test('submitting with is_veteran persists the veteran flag', function () {
    $this->post(route('contact.store'), contactPayload(['is_veteran' => '1']))
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::sole()->is_veteran)->toBeTrue();
});

test('is_veteran defaults to false when omitted', function () {
    $this->post(route('contact.store'), contactPayload())
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::sole()->is_veteran)->toBeFalse();
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
