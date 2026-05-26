<?php

namespace App\Http\Controllers;

use App\Concerns\DetectsFormSpam;
use App\Enums\InquirySource;
use App\Http\Requests\StoreSeoAssessmentRequest;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SeoAssessmentController extends Controller
{
    use DetectsFormSpam;

    public function show(): View
    {
        return view('seo-assessment');
    }

    public function store(StoreSeoAssessmentRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request, 'company_url')) {
            return $this->thankYouRedirect();
        }

        $inquiry = ContactInquiry::create([
            ...$request->validated(),
            'source' => InquirySource::SeoAssessment,
        ]);

        $recipients = config('admin.emails', []);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new ContactInquiryReceived($inquiry));
        }

        return $this->thankYouRedirect();
    }

    private function thankYouRedirect(): RedirectResponse
    {
        return redirect()
            ->route('seo-assessment.show')
            ->with('status', 'Thanks — your free SEO assessment request is in. We\'ll review your site and get back to you within two business days.');
    }
}
