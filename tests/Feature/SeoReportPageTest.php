<?php

use App\Enums\SeoReportStatus;
use App\Jobs\GenerateSeoReport;
use App\Models\SeoReport;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Crypt;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the seo report tool', function () {
    $this->get(route('seo-report.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('SeoReport/Index')
            ->has('industries')
            ->has('startedAt'));
});

it('creates a pending report and dispatches the job', function () {
    Bus::fake();

    $response = $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Home Services',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ]);

    $response->assertOk()->assertJsonStructure(['token', 'statusUrl', 'unlockUrl']);

    $report = SeoReport::sole();
    expect($report->status)->toBe(SeoReportStatus::Pending)
        ->and($report->host)->toBe('example.com')
        ->and($report->industry)->toBe('Home Services');

    Bus::assertDispatched(GenerateSeoReport::class, fn ($job) => $job->report->is($report));
});

it('silently ignores honeypot submissions', function () {
    Bus::fake();

    $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Home Services',
        'company_url' => 'http://spam.test',
        'started_at' => validStartedAt(),
    ])->assertOk();

    expect(SeoReport::count())->toBe(0);
    Bus::assertNotDispatched(GenerateSeoReport::class);
});

it('silently ignores submissions filled out too quickly', function () {
    Bus::fake();

    $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Home Services',
        'company_url' => '',
        'started_at' => Crypt::encryptString((string) now()->timestamp),
    ])->assertOk();

    expect(SeoReport::count())->toBe(0);
    Bus::assertNotDispatched(GenerateSeoReport::class);
});

it('treats a tampered timestamp as spam', function () {
    Bus::fake();

    $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Home Services',
        'company_url' => '',
        'started_at' => 'not-encrypted',
    ])->assertOk();

    expect(SeoReport::count())->toBe(0);
    Bus::assertNotDispatched(GenerateSeoReport::class);
});

it('rejects unsafe and private urls', function (string $url) {
    Bus::fake();

    $this->postJson(route('seo-report.store'), [
        'url' => $url,
        'industry' => 'Home Services',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ])->assertStatus(422)->assertJsonValidationErrorFor('url');

    expect(SeoReport::count())->toBe(0);
    Bus::assertNotDispatched(GenerateSeoReport::class);
})->with([
    'localhost' => ['http://localhost'],
    'loopback ip' => ['http://127.0.0.1'],
    'private ip' => ['http://192.168.0.1'],
    'metadata ip' => ['http://169.254.169.254'],
    'non-web port' => ['http://example.com:22'],
    'non-http scheme' => ['ftp://example.com'],
]);

it('validates the industry against the allow-list', function () {
    $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Underwater Basket Weaving',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ])->assertStatus(422)->assertJsonValidationErrorFor('industry');
});

it('rate limits report submissions', function () {
    Bus::fake();

    foreach (range(1, 5) as $i) {
        $this->postJson(route('seo-report.store'), [
            'url' => 'https://example.com',
            'industry' => 'Home Services',
            'company_url' => '',
            'started_at' => validStartedAt(),
        ])->assertOk();
    }

    $this->postJson(route('seo-report.store'), [
        'url' => 'https://example.com',
        'industry' => 'Home Services',
        'company_url' => '',
        'started_at' => validStartedAt(),
    ])->assertStatus(429);
});
