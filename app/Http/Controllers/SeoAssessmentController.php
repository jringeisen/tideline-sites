<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeoAssessmentRequest;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SeoAssessmentController extends Controller
{
    /**
     * Minimum seconds between form render and submission. Anything faster is
     * assumed to be a bot.
     */
    private const MIN_FILL_SECONDS = 3;

    public function show(): View
    {
        return view('seo-assessment');
    }

    public function store(StoreSeoAssessmentRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request)) {
            return $this->thankYouRedirect();
        }

        $inquiry = ContactInquiry::create([
            ...$request->validated(),
            'source' => ContactInquiry::SOURCE_SEO_ASSESSMENT,
        ]);

        $recipients = config('admin.emails', []);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new ContactInquiryReceived($inquiry));
        }

        return $this->thankYouRedirect();
    }

    private function looksLikeSpam(StoreSeoAssessmentRequest $request): bool
    {
        if (filled($request->input('company_url'))) {
            return true;
        }

        try {
            $startedAt = (int) Crypt::decryptString((string) $request->input('started_at', ''));
        } catch (DecryptException) {
            return true;
        }

        return (time() - $startedAt) < self::MIN_FILL_SECONDS;
    }

    private function thankYouRedirect(): RedirectResponse
    {
        return redirect()
            ->route('seo-assessment.show')
            ->with('status', 'Thanks — your free SEO assessment request is in. We\'ll review your site and get back to you within two business days.');
    }
}
