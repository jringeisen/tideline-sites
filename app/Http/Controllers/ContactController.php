<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactInquiryRequest;
use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Minimum seconds between form render and submission. Anything faster is
     * assumed to be a bot.
     */
    private const MIN_FILL_SECONDS = 3;

    public function show(): View
    {
        return view('contact', [
            'selectedPlan' => request()->query('plan'),
            'isVeteran' => request()->boolean('veteran'),
        ]);
    }

    public function store(StoreContactInquiryRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request)) {
            return $this->thankYouRedirect();
        }

        $inquiry = ContactInquiry::create($request->validated());

        $recipients = config('admin.emails', []);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new ContactInquiryReceived($inquiry));
        }

        return $this->thankYouRedirect();
    }

    private function looksLikeSpam(StoreContactInquiryRequest $request): bool
    {
        if (filled($request->input('website'))) {
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
            ->route('contact.show')
            ->with('status', 'Thanks — we got your message and will be in touch within one business day.');
    }
}
