<?php

/*
|--------------------------------------------------------------------------
| CREAM site configuration
|--------------------------------------------------------------------------
| Ported from the static site's data/site.json so the Laravel app is
| self-contained (the static data/ folder is not deployed with it).
| Phase 7 layers an editable Settings table on top of these defaults.
|
| Canonical/OG URLs use APP_URL (never a hard-coded domain).
*/

return [

    'name' => 'Credence Real Estate',
    'legal_name' => 'Credence Real Estate & Asset Management',
    'parent' => 'Credence Holdings Global',
    'tagline' => 'Building Value. Creating Legacy.',
    'location_line' => 'Accra HQ · Diaspora desks in the UK and USA',
    'founded' => '2022',
    'hours' => 'Mon–Fri',

    'phone' => [
        'display' => '+233 24 442 7440',
        'tel' => '+233244427440',
    ],

    'whatsapp' => [
        'display' => '+233 24 412 0039',
        'number' => '233244120039',
    ],

    'email' => 'info@credencerealestateandassetmgt.com',

    // Where enquiry notifications and moderation digests / alerts go
    // (Phase 7 makes this editable in Settings).
    // Use `?:` (not env()'s 2nd arg) so a present-but-empty MODERATION_EMAIL
    // still falls back — env() only applies its default when the var is absent.
    'moderation_email' => env('MODERATION_EMAIL') ?: 'info@credencerealestateandassetmgt.com',

    'address' => [
        'hq' => 'Bawuah Junction, East Legon, Greater Accra, Ghana',
        'district' => 'East Legon',
        'region' => 'Greater Accra',
        'country' => 'Ghana',
        'us_holdings' => '805 Horizon Way, Manchester, CT 06042, USA',
        'lat' => 5.6423527,
        'lng' => -0.1611202,
    ],

    'social' => [
        'instagram' => ['handle' => '@credence_realestateandassetmgt', 'url' => 'https://www.instagram.com/credence_realestateandassetmgt'],
        'tiktok' => ['handle' => '@credencerealestat', 'url' => 'https://www.tiktok.com/@credencerealestat'],
        'youtube' => ['handle' => '@CredenceRealEstateAssetManagem', 'url' => 'https://www.youtube.com/@CredenceRealEstateAssetManagem'],
        'facebook' => ['handle' => 'Credence Real Estate and Asset Management', 'url' => 'https://www.facebook.com/people/Credence-Real-Estate-and-Asset-Management/100088681766316'],
    ],

    'show' => [
        'name' => 'The Signature Home Show',
        'channel' => 'https://www.youtube.com/@TheSignatureHomeShow',
        'latest_video_id' => 'R1tyNIg0JXA',
    ],

    /*
    |--------------------------------------------------------------------------
    | Featured project campaign (site-wide promo popup)
    |--------------------------------------------------------------------------
    | Drives the attention popup shown across the public site and links to the
    | bespoke /projects/{slug} landing page. Set enabled => false to retire it.
    | Credence is the sole point of contact — the popup/page never link out to
    | the developer or masterplanner. Prices/timeline are deliberately "on
    | request" so every lead comes through us.
    */
    'campaign' => [
        'enabled' => true,
        'slug' => 'sunridge',
        'name' => 'SUNRIDGE',
        'subtitle' => 'University City of Ghana',
        'eyebrow' => 'Now selling · 500 homes',
        'location' => 'University of Ghana enclave · Accra',
        'teaser' => '500 homes released to the public',
        'blurb' => 'An 80-acre gated villa city beside the University of Ghana — detached villas, townhouses, a central clubhouse and retail.',
        'image' => '/assets/projects/sunridge/popup.jpg',
        'href' => '/projects/sunridge',
        'foot' => 'Prices and floor plans on request — speak to Credence directly.',
    ],

];
