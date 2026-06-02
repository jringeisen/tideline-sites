<?php

use App\Ai\Agents\SeoReportAnalyst;
use App\Enums\SeoReportStatus;
use App\Jobs\GenerateSeoReport;
use App\Models\SeoReport;
use App\Services\WebsiteSeoScanner;
use Database\Factories\SeoReportFactory;
use Illuminate\Support\Facades\Http;

function runJob(SeoReport $report): void
{
    (new GenerateSeoReport($report))->handle(app(WebsiteSeoScanner::class));
}

it('scans the site and stores the generated report', function () {
    Http::fake([
        '*' => Http::response(
            '<html><head><title>Acme Plumbing</title></head><body><h1>We fix pipes</h1></body></html>',
            200,
            ['Content-Type' => 'text/html'],
        ),
    ]);

    SeoReportAnalyst::fake([SeoReportFactory::sampleReport()]);

    $report = SeoReport::factory()->create([
        'url' => 'https://example.com',
        'host' => 'example.com',
    ]);

    runJob($report);

    $report->refresh();
    expect($report->status)->toBe(SeoReportStatus::Completed)
        ->and($report->score)->toBe(72)
        ->and($report->report)->toHaveKey('summary')
        ->and($report->raw_signals)->toHaveKey('title');

    SeoReportAnalyst::assertPrompted(fn () => true);
});

it('marks the report failed when the site is unreachable', function () {
    Http::fake(['*' => Http::response('nope', 500)]);

    SeoReportAnalyst::fake();

    $report = SeoReport::factory()->create([
        'url' => 'https://example.com',
        'host' => 'example.com',
    ]);

    runJob($report);

    $report->refresh();
    expect($report->status)->toBe(SeoReportStatus::Failed)
        ->and($report->error)->not->toBeNull();

    SeoReportAnalyst::assertNeverPrompted();
});

it('marks the report failed for non-html responses', function () {
    Http::fake([
        '*' => Http::response('{"json":true}', 200, ['Content-Type' => 'application/json']),
    ]);

    SeoReportAnalyst::fake();

    $report = SeoReport::factory()->create([
        'url' => 'https://example.com',
        'host' => 'example.com',
    ]);

    runJob($report);

    expect($report->refresh()->status)->toBe(SeoReportStatus::Failed);
    SeoReportAnalyst::assertNeverPrompted();
});
