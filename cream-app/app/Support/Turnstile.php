<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class Turnstile
{
    /**
     * Whether Turnstile is configured (and therefore enforced).
     */
    public function enabled(): bool
    {
        return (bool) config('turnstile.secret');
    }

    /**
     * Verify a Turnstile token against Cloudflare's siteverify endpoint.
     * Returns true when verification is not enforced (no secret configured).
     */
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(config('turnstile.verify_url'), [
                    'secret' => config('turnstile.secret'),
                    'response' => $token,
                    'remoteip' => $ip,
                ]);
        } catch (\Throwable $e) {
            report($e);

            // Fail closed: if Cloudflare cannot be reached, reject the submission.
            return false;
        }

        return (bool) $response->json('success', false);
    }
}
