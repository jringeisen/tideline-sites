<?php

use App\Enums\InquirySource;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use App\Models\SeoReport;
use Illuminate\Support\Facades\Mail;

it('reports pending status while processing', function () {
    $report = SeoReport::factory()->create();

    $this->getJson(route('seo-report.status', $report))
        ->assertOk()
        ->assertJson(['status' => 'pending']);
});

it('surfaces a friendly message when the report failed', function () {
    $report = SeoReport::factory()->failed()->create();

    $this->getJson(route('seo-report.status', $report))
        ->assertOk()
        ->assertJson(['status' => 'failed'])
        ->assertJsonStructure(['status', 'message']);
});

it('hides the full report behind the email gate', function () {
    $report = SeoReport::factory()->completed()->create();

    $response = $this->getJson(route('seo-report.status', $report))
        ->assertOk()
        ->assertJson([
            'status' => 'completed',
            'locked' => true,
        ])
        ->assertJsonStructure(['score', 'summary', 'sectionTitles', 'previewItems', 'lockedCount']);

    // The actionable detail and gated sections must never reach a locked client.
    $response->assertJsonMissingPath('report');
    expect($response->json('previewItems.0'))->not->toHaveKey('detail');
});

it('reveals the full report once unlocked', function () {
    $report = SeoReport::factory()->unlocked()->create();

    $this->getJson(route('seo-report.status', $report))
        ->assertOk()
        ->assertJson([
            'status' => 'completed',
            'locked' => false,
        ])
        ->assertJsonStructure(['report' => ['summary', 'sections', 'industry_tips', 'google_business_profile']]);
});

it('captures the lead and reveals the report on unlock', function () {
    Mail::fake();
    config(['admin.emails' => ['admin@example.com']]);

    $report = SeoReport::factory()->completed()->create();

    $response = $this->postJson(route('seo-report.unlock', $report), [
        'email' => 'owner@business.test',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ]);

    $response->assertOk()->assertJson(['status' => 'completed', 'locked' => false]);

    $report->refresh();
    expect($report->isUnlocked())->toBeTrue()
        ->and($report->email)->toBe('owner@business.test');

    $inquiry = ContactInquiry::sole();
    expect($inquiry->source)->toBe(InquirySource::SeoReport)
        ->and($inquiry->email)->toBe('owner@business.test')
        ->and($report->contact_inquiry_id)->toBe($inquiry->id);

    Mail::assertQueued(ContactInquiryReceived::class);
});

it('does not capture a lead for honeypot unlock attempts', function () {
    Mail::fake();

    $report = SeoReport::factory()->completed()->create();

    $this->postJson(route('seo-report.unlock', $report), [
        'email' => 'owner@business.test',
        'company_url' => 'http://spam.test',
        'started_at' => validStartedAt(),
    ])->assertOk()->assertJson(['locked' => true]);

    expect(ContactInquiry::count())->toBe(0);
    expect($report->refresh()->isUnlocked())->toBeFalse();
    Mail::assertNothingSent();
});

it('validates the unlock email', function () {
    $report = SeoReport::factory()->completed()->create();

    $this->postJson(route('seo-report.unlock', $report), [
        'email' => 'not-an-email',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ])->assertStatus(422)->assertJsonValidationErrorFor('email');
});

it('rate limits unlock attempts', function () {
    $report = SeoReport::factory()->completed()->create();

    foreach (range(1, 10) as $i) {
        $this->postJson(route('seo-report.unlock', $report), [
            'email' => 'owner@business.test',
            'company_url' => '',
            'started_at' => validStartedAt(),
        ])->assertOk();
    }

    $this->postJson(route('seo-report.unlock', $report), [
        'email' => 'owner@business.test',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ])->assertStatus(429);
});
