<?php

namespace App\Jobs;

use App\Ai\Agents\SeoReportAnalyst;
use App\Enums\SeoReportStatus;
use App\Models\SeoReport;
use App\Services\WebsiteSeoScanner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GenerateSeoReport implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job may run before timing out.
     */
    public int $timeout = 60;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    public function __construct(public SeoReport $report) {}

    /**
     * Execute the job.
     */
    public function handle(WebsiteSeoScanner $scanner): void
    {
        if ($this->report->status === SeoReportStatus::Completed) {
            return;
        }

        $this->report->update(['status' => SeoReportStatus::Processing]);

        try {
            $signals = $scanner->scan($this->report->url);

            $response = (new SeoReportAnalyst)->prompt(
                $scanner->toPromptInput($signals, $this->report->industry),
                model: config('seo-report.model'),
            );

            /** @var array<string, mixed> $report */
            $report = json_decode((string) $response, true) ?? [];

            $this->report->update([
                'raw_signals' => $signals,
                'report' => $report,
                'score' => $report['score'] ?? null,
                'status' => SeoReportStatus::Completed,
            ]);
        } catch (Throwable $e) {
            $this->markFailed($e);
        }
    }

    /**
     * Handle a job failure that escaped the handle() try/catch (e.g. timeout).
     */
    public function failed(Throwable $e): void
    {
        $this->markFailed($e);
    }

    private function markFailed(Throwable $e): void
    {
        Log::warning('SEO report generation failed.', [
            'seo_report_id' => $this->report->id,
            'host' => $this->report->host,
            'exception' => $e::class,
            'message' => $e->getMessage(),
        ]);

        $this->report->update([
            'status' => SeoReportStatus::Failed,
            'error' => $e instanceof RuntimeException
                ? $e->getMessage()
                : 'We hit a snag generating your report. Please try again.',
        ]);
    }
}
