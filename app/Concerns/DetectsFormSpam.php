<?php

namespace App\Concerns;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/**
 * Shared bot-detection for public forms: a honeypot field that should stay
 * empty plus an encrypted render timestamp that must be at least
 * {@see MIN_FILL_SECONDS} old before submission.
 */
trait DetectsFormSpam
{
    /**
     * Minimum seconds between form render and submission. Anything faster is
     * assumed to be a bot.
     */
    private const MIN_FILL_SECONDS = 3;

    private function looksLikeSpam(Request $request, string $honeypotField): bool
    {
        if (filled($request->input($honeypotField))) {
            return true;
        }

        try {
            $startedAt = (int) Crypt::decryptString((string) $request->input('started_at', ''));
        } catch (DecryptException) {
            return true;
        }

        return (time() - $startedAt) < self::MIN_FILL_SECONDS;
    }
}
