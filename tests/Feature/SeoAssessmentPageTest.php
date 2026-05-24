<?php

use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function seoAssessmentPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane Beachgoer',
        'email' => 'jane@example.com',
        'business_name' => 'Beachgoer Rentals',
        'website' => 'https://beachgoer.example.com',
        'company_url' => '',
        'started_at' => Crypt::encryptString(now()->subSeconds(10)->timestamp),
    ], $overrides);
}

test('seo assessment page renders the form with all expected fields', function () {
    $this->get(route('seo-assessment.show'))
        ->assertOk()
        ->assertSee('See where your site', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="business_name"', false)
        ->assertSee('name="website"', false)
        ->assertSee('name="company_url"', false)
        ->assertSee('name="started_at"', false);
});

test('submitting valid data persists the inquiry and redirects with a success flash', function () {
    $this->post(route('seo-assessment.store'), seoAssessmentPayload())
        ->assertRedirect(route('seo-assessment.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(1);

    $inquiry = ContactInquiry::sole();
    expect($inquiry->name)->toBe('Jane Beachgoer')
        ->and($inquiry->email)->toBe('jane@example.com')
        ->and($inquiry->business_name)->toBe('Beachgoer Rentals')
        ->and($inquiry->website)->toBe('https://beachgoer.example.com')
        ->and($inquiry->source)->toBe(ContactInquiry::SOURCE_SEO_ASSESSMENT)
        ->and($inquiry->phone)->toBeNull()
        ->and($inquiry->plan)->toBeNull()
        ->and($inquiry->message)->toBeNull();
});

test('submitting without required fields fails validation', function () {
    $this->post(route('seo-assessment.store'), [
        'started_at' => Crypt::encryptString(now()->subSeconds(10)->timestamp),
    ])->assertSessionHasErrors(['name', 'email', 'business_name', 'website']);

    expect(ContactInquiry::count())->toBe(0);
});

test('an invalid website url is rejected', function () {
    $this->post(route('seo-assessment.store'), seoAssessmentPayload(['website' => 'not a url']))
        ->assertSessionHasErrors('website');

    expect(ContactInquiry::count())->toBe(0);
});

test('a filled honeypot silently drops the submission with the same success flash', function () {
    $this->post(route('seo-assessment.store'), seoAssessmentPayload(['company_url' => 'http://spam.example']))
        ->assertRedirect(route('seo-assessment.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('a submission faster than the time-trap is silently dropped', function () {
    $this->post(route('seo-assessment.store'), seoAssessmentPayload([
        'started_at' => Crypt::encryptString(now()->timestamp),
    ]))
        ->assertRedirect(route('seo-assessment.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('a missing or tampered started_at is treated as spam', function () {
    $this->post(route('seo-assessment.store'), seoAssessmentPayload(['started_at' => 'not-a-valid-token']))
        ->assertRedirect(route('seo-assessment.show'))
        ->assertSessionHas('status');

    expect(ContactInquiry::count())->toBe(0);
});

test('a successful submission queues the admin notification with an SEO subject', function () {
    config()->set('admin.emails', ['admin@example.com']);
    Mail::fake();

    $this->post(route('seo-assessment.store'), seoAssessmentPayload())
        ->assertRedirect(route('seo-assessment.show'));

    $inquiry = ContactInquiry::sole();

    Mail::assertQueued(ContactInquiryReceived::class, function (ContactInquiryReceived $mail) use ($inquiry) {
        return $mail->inquiry->is($inquiry)
            && $mail->hasTo('admin@example.com')
            && str_contains($mail->envelope()->subject, 'SEO assessment');
    });
});

test('the seo assessment form is rate limited per IP', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('seo-assessment.store'), seoAssessmentPayload(['email' => "valid{$i}@example.com"]))
            ->assertRedirect(route('seo-assessment.show'));
    }

    $this->post(route('seo-assessment.store'), seoAssessmentPayload(['email' => 'sixth@example.com']))
        ->assertStatus(429);

    expect(ContactInquiry::count())->toBe(5);
});
