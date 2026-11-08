<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile
    |--------------------------------------------------------------------------
    |
    | Keys for Cloudflare Turnstile, used on the staff login and on every
    | public form. When the secret is empty, verification is skipped (useful
    | for automated tests). Locally, use Cloudflare's "always passes" test
    | keys so no real challenge is shown:
    |   site key:   1x00000000000000000000AA
    |   secret key: 1x0000000000000000000000000000000AA
    |
    */

    'sitekey' => env('TURNSTILE_SITEKEY', ''),

    'secret' => env('TURNSTILE_SECRET', ''),

    'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',

];
