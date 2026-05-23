<?php

use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    config()->set('admin.emails', ['admin@example.com', 'second@example.com']);
});

function validContactPayload(array $overrides = []): array
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

test('a successful submission queues an admin notification to every admin email', function () {
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload())
        ->assertRedirect(route('contact.show'));

    $inquiry = ContactInquiry::sole();

    Mail::assertQueued(ContactInquiryReceived::class, function (ContactInquiryReceived $mail) use ($inquiry) {
        return $mail->inquiry->is($inquiry)
            && $mail->hasTo('admin@example.com')
            && $mail->hasTo('second@example.com');
    });
});

test('the notification is not sent when no admin emails are configured', function () {
    config()->set('admin.emails', []);
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload())
        ->assertRedirect(route('contact.show'));

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
});

test('a spam submission does not trigger the notification', function () {
    Mail::fake();

    $this->post(route('contact.store'), validContactPayload(['website' => 'http://spam.example']))
        ->assertRedirect(route('contact.show'));

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
    expect(ContactInquiry::count())->toBe(0);
});

test('the mailable renders with inquiry details and an admin link', function () {
    $inquiry = ContactInquiry::factory()->create([
        'name' => 'Pat Sailor',
        'email' => 'pat@example.com',
        'message' => 'Need a refresh of my charter site.',
    ]);

    $rendered = (new ContactInquiryReceived($inquiry))->render();

    expect($rendered)
        ->toContain('Pat Sailor')
        ->toContain('pat@example.com')
        ->toContain('Need a refresh of my charter site.')
        ->toContain(route('admin.contact-inquiries.show', $inquiry));
});
