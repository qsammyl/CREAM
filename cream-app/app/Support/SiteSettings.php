<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Editable site settings.
 *
 * Each field is stored in the `settings` table under a flat key. Fields with a
 * `path` overlay the matching `config('cream.*')` default at boot, so every
 * existing `config('cream.…')` read in the app and views picks up admin edits
 * automatically. Fields without a `path` (e.g. retention) are read directly
 * via Setting::get() where they are used.
 */
class SiteSettings
{
    /**
     * @return array<string, array<int, array<string, string|null>>>
     */
    public static function schema(): array
    {
        return [
            'Contact' => [
                ['key' => 'phone_display', 'path' => 'phone.display', 'label' => 'Phone (shown)', 'type' => 'text'],
                ['key' => 'phone_tel', 'path' => 'phone.tel', 'label' => 'Phone (tel: link, no spaces)', 'type' => 'text'],
                ['key' => 'whatsapp_display', 'path' => 'whatsapp.display', 'label' => 'WhatsApp (shown)', 'type' => 'text'],
                ['key' => 'whatsapp_number', 'path' => 'whatsapp.number', 'label' => 'WhatsApp (digits only, e.g. 233…)', 'type' => 'text'],
                ['key' => 'email', 'path' => 'email', 'label' => 'Public email', 'type' => 'email'],
                ['key' => 'moderation_email', 'path' => 'moderation_email', 'label' => 'Enquiry & moderation inbox', 'type' => 'email'],
                ['key' => 'hours', 'path' => 'hours', 'label' => 'Opening hours', 'type' => 'text'],
                ['key' => 'location_line', 'path' => 'location_line', 'label' => 'Location line (header/footer)', 'type' => 'text'],
            ],
            'Address' => [
                ['key' => 'address_hq', 'path' => 'address.hq', 'label' => 'Accra HQ address', 'type' => 'text'],
                ['key' => 'address_us', 'path' => 'address.us_holdings', 'label' => 'US holdings address', 'type' => 'text'],
            ],
            'Social links' => [
                ['key' => 'social_instagram', 'path' => 'social.instagram.url', 'label' => 'Instagram URL', 'type' => 'url'],
                ['key' => 'social_tiktok', 'path' => 'social.tiktok.url', 'label' => 'TikTok URL', 'type' => 'url'],
                ['key' => 'social_youtube', 'path' => 'social.youtube.url', 'label' => 'YouTube URL', 'type' => 'url'],
                ['key' => 'social_facebook', 'path' => 'social.facebook.url', 'label' => 'Facebook URL', 'type' => 'url'],
            ],
            'Signature Home Show' => [
                ['key' => 'show_name', 'path' => 'show.name', 'label' => 'Show name', 'type' => 'text'],
                ['key' => 'show_channel', 'path' => 'show.channel', 'label' => 'Channel URL', 'type' => 'url'],
                ['key' => 'show_latest_video', 'path' => 'show.latest_video_id', 'label' => 'Latest video ID (YouTube)', 'type' => 'text'],
            ],
            'Data retention' => [
                ['key' => 'retention_enquiries_days', 'path' => null, 'label' => 'Delete closed/spam enquiries after N days (0 = keep forever)', 'type' => 'number'],
                ['key' => 'retention_comments_days', 'path' => null, 'label' => 'Delete spam comments after N days (0 = keep forever)', 'type' => 'number'],
                ['key' => 'retention_analytics_days', 'path' => null, 'label' => 'Delete analytics page-views after N days (0 = keep forever)', 'type' => 'number'],
            ],
        ];
    }

    /** Flat list of every field across all groups. */
    public static function fields(): array
    {
        return array_merge(...array_values(self::schema()));
    }

    /**
     * Overlay stored settings onto config('cream.*'). Called at boot.
     * Safe before the table exists (fresh install, pre-migrate).
     */
    public static function apply(): void
    {
        try {
            foreach (self::fields() as $field) {
                if (! $field['path']) {
                    continue;
                }
                $value = Setting::get($field['key']);
                if ($value !== null && $value !== '') {
                    config(['cream.'.$field['path'] => $value]);
                }
            }
        } catch (\Throwable) {
            // No settings table yet (e.g. before first migrate) — use config defaults.
        }
    }

    /** The current value for a field: stored override, else the config default. */
    public static function current(array $field): mixed
    {
        $stored = Setting::get($field['key']);
        if ($stored !== null) {
            return $stored;
        }

        return $field['path'] ? config('cream.'.$field['path']) : null;
    }
}
