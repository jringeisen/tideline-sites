<?php

use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Mail;

test('a submission whose email matches a spam-marked inquiry is silently dropped', function () {
    Mail::fake();
    ContactInquiry::factory()->spam()->create(['email' => 'spammer@example.com']);

    $this->post(route('contact.store'), contactPayload(['email' => 'spammer@example.com']))
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(1);
    Mail::assertNothingSent();
});

test('email matching against the blocklist is case-insensitive', function () {
    ContactInquiry::factory()->spam()->create(['email' => 'spammer@example.com']);

    $this->post(route('contact.store'), contactPayload(['email' => 'SPAMMER@EXAMPLE.COM']))
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::count())->toBe(1);
});

test('a submission whose IP matches a spam-marked inquiry is silently dropped', function () {
    Mail::fake();
    ContactInquiry::factory()->spam()->create([
        'email' => 'someone-else@example.com',
        'ip_address' => '203.0.113.10',
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
        ->post(route('contact.store'), contactPayload(['email' => 'fresh@example.com']))
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::count())->toBe(1);
    Mail::assertNothingSent();
});

test('a clean submission from a non-blocked email and IP is stored with its IP', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.5'])
        ->post(route('contact.store'), contactPayload(['email' => 'clean@example.com']))
        ->assertRedirect(route('contact.show'));

    $inquiry = ContactInquiry::sole();
    expect($inquiry->email)->toBe('clean@example.com')
        ->and($inquiry->ip_address)->toBe('198.51.100.5')
        ->and($inquiry->is_spam)->toBeFalse();
});

test('a non-spam inquiry with a matching email does not block submissions', function () {
    ContactInquiry::factory()->create(['email' => 'regular@example.com', 'is_spam' => false]);

    $this->post(route('contact.store'), contactPayload(['email' => 'regular@example.com']))
        ->assertRedirect(route('contact.show'));

    expect(ContactInquiry::where('email', 'regular@example.com')->count())->toBe(2);
});

test('the seo assessment form also honors the blocklist', function () {
    Mail::fake();
    ContactInquiry::factory()->spam()->create(['email' => 'spammer@example.com']);

    $this->post(route('seo-assessment.store'), seoAssessmentPayload(['email' => 'spammer@example.com']))
        ->assertRedirect(route('seo-assessment.show'));

    expect(ContactInquiry::count())->toBe(1);
    Mail::assertNothingSent();
});
