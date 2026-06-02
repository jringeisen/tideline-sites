<?php

namespace App\Http\Controllers;

use App\Concerns\DetectsFormSpam;
use App\Enums\InquirySource;
use App\Enums\SeoReportStatus;
use App\Http\Requests\StoreSeoReportRequest;
use App\Http\Requests\UnlockSeoReportRequest;
use App\Jobs\GenerateSeoReport;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use App\Models\SeoReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class SeoReportController extends Controller
{
    use DetectsFormSpam;

    /**
     * The number of preview items shown before the report is unlocked.
     */
    private const PREVIEW_ITEMS = 2;

    public function show(): Response
    {
        return Inertia::render('SeoReport/Index', [
            'industries' => config('seo-report.industries'),
            'startedAt' => Crypt::encryptString((string) now()->timestamp),
        ]);
    }

    public function store(StoreSeoReportRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Silently no-op for bots and previously-flagged abusers, returning a
        // benign-looking response so detection isn't revealed.
        if ($this->looksLikeSpam($request, 'company_url')
            || ContactInquiry::isBlockedSubmission(null, $request->ip())) {
            return $this->acceptedResponse((string) Str::ulid());
        }

        $report = SeoReport::create([
            'url' => $validated['url'],
            'host' => parse_url($validated['url'], PHP_URL_HOST) ?: $validated['url'],
            'industry' => $validated['industry'],
            'status' => SeoReportStatus::Pending,
            'ip_address' => $request->ip(),
        ]);

        GenerateSeoReport::dispatch($report);

        return $this->acceptedResponse($report->token);
    }

    public function status(SeoReport $seoReport): JsonResponse
    {
        return response()->json($this->statusPayload($seoReport));
    }

    public function unlock(UnlockSeoReportRequest $request, SeoReport $seoReport): JsonResponse
    {
        $email = $request->validated('email');

        // Capture the lead unless this looks like spam or a blocked abuser, in
        // which case we return the still-locked payload without revealing it.
        if (! $this->looksLikeSpam($request, 'company_url')
            && ! ContactInquiry::isBlockedSubmission($email, $request->ip())
            && ! $seoReport->isUnlocked()) {
            $inquiry = ContactInquiry::create([
                'name' => $seoReport->host,
                'email' => $email,
                'website' => $seoReport->url,
                'source' => InquirySource::SeoReport,
                'ip_address' => $request->ip(),
            ]);

            $seoReport->update([
                'email' => $email,
                'email_captured_at' => now(),
                'contact_inquiry_id' => $inquiry->id,
            ]);

            $recipients = config('admin.emails', []);
            if (! empty($recipients)) {
                Mail::to($recipients)->send(new ContactInquiryReceived($inquiry));
            }
        }

        return response()->json($this->statusPayload($seoReport->refresh()));
    }

    private function acceptedResponse(string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'statusUrl' => route('seo-report.status', ['seoReport' => $token]),
            'unlockUrl' => route('seo-report.unlock', ['seoReport' => $token]),
        ]);
    }

    /**
     * Build the polling payload, gating the full report behind email capture.
     *
     * @return array<string, mixed>
     */
    private function statusPayload(SeoReport $report): array
    {
        if ($report->status === SeoReportStatus::Failed) {
            return [
                'status' => SeoReportStatus::Failed->value,
                'message' => $report->error ?: 'We could not generate a report for that site.',
            ];
        }

        if ($report->status !== SeoReportStatus::Completed) {
            return ['status' => $report->status->value];
        }

        if ($report->isUnlocked()) {
            return [
                'status' => SeoReportStatus::Completed->value,
                'locked' => false,
                'score' => $report->score,
                'report' => $report->report,
            ];
        }

        return $this->teaser($report);
    }

    /**
     * The locked teaser: score, summary, section titles, and a couple of
     * preview items with no actionable detail.
     *
     * @return array<string, mixed>
     */
    private function teaser(SeoReport $report): array
    {
        $payload = $report->report ?? [];
        $sections = $payload['sections'] ?? [];

        $allItems = collect($sections)->flatMap(fn (array $section): array => $section['items'] ?? []);

        $previewItems = $allItems->take(self::PREVIEW_ITEMS)
            ->map(fn (array $item): array => [
                'title' => $item['title'] ?? '',
                'priority' => $item['priority'] ?? 'medium',
                'category' => $item['category'] ?? null,
            ])
            ->values()
            ->all();

        $hiddenCount = ($allItems->count() - count($previewItems))
            + count($payload['industry_tips'] ?? [])
            + count($payload['google_business_profile']['steps'] ?? []);

        return [
            'status' => SeoReportStatus::Completed->value,
            'locked' => true,
            'score' => $report->score,
            'summary' => $payload['summary'] ?? null,
            'sectionTitles' => array_values(array_map(
                fn (array $section): string => $section['title'] ?? '',
                $sections,
            )),
            'previewItems' => $previewItems,
            'lockedCount' => max($hiddenCount, 0),
        ];
    }
}
