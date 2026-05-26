<?php

namespace App\Http\Controllers;

use App\Concerns\DetectsFormSpam;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    use DetectsFormSpam;

    public function show(): View
    {
        return view('contact', [
            'selectedPlan' => request()->query('plan'),
            'isVeteran' => request()->boolean('veteran'),
        ]);
    }

    public function store(StoreContactInquiryRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request, 'website')) {
            return $this->thankYouRedirect();
        }

        $inquiry = ContactInquiry::create($request->validated());

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
