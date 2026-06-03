<?php

namespace App\Http\Controllers;

use App\Concerns\DetectsFormSpam;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use App\Support\MarketingSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    use DetectsFormSpam;

    public function show(): Response
    {
        return Inertia::render('Contact', [
            'selectedPlan' => request()->query('plan'),
            'isVeteran' => request()->boolean('veteran'),
            'startedAt' => Crypt::encryptString((string) now()->timestamp),
            'status' => session('status'),
            'meta' => [
                'title' => 'Contact All American Web Design — Custom Websites, Built in America',
                'description' => "Tell us about your business and we'll be in touch within one business day. Veteran-owned, American-made web design for small businesses nationwide.",
                'canonical' => url()->current(),
            ],
            'schema' => [
                MarketingSchema::contactPage(),
            ],
        ]);
    }

    public function store(StoreContactInquiryRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request, 'website')) {
            return $this->thankYouRedirect();
        }

        if (ContactInquiry::isBlockedSubmission($request->validated('email'), $request->ip())) {
            return $this->thankYouRedirect();
        }

        $inquiry = ContactInquiry::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
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
            ->route('contact.show')
            ->with('status', 'Thanks — we got your message and will be in touch within one business day.');
    }
}
