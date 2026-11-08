<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Record an auditable action (create, update, delete, approve, reject,
     * login, logout, ...) against an optional subject record.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function log(
        string $action,
        ?Model $subject = null,
        ?Request $request = null,
        array $properties = [],
        ?User $actor = null,
    ): ActivityLog {
        $request ??= request();
        $actor ??= Auth::user();

        return ActivityLog::create([
            'user_id' => $actor?->getKey(),
            'action' => $action,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties ?: null,
            'ip_hash' => self::hashIp($request?->ip()),
            'user_agent' => self::trimUserAgent($request?->userAgent()),
        ]);
    }

    public static function logFailedLogin(string $email, ?Request $request = null): ActivityLog
    {
        $request ??= request();

        return ActivityLog::create([
            'user_id' => null,
            'action' => 'failed_login',
            'properties' => ['email' => $email],
            'ip_hash' => self::hashIp($request?->ip()),
            'user_agent' => self::trimUserAgent($request?->userAgent()),
        ]);
    }

    public static function logLockout(?Request $request = null): ActivityLog
    {
        return self::log('lockout', null, $request);
    }

    /**
     * HMAC the IP for abuse control / audit — the raw IP is never stored.
     * (The analytics subsystem uses its own rotating daily salt; this audit
     * log uses the stable app key so admins can correlate a user's actions.)
     */
    public static function hashIp(?string $ip): ?string
    {
        if (empty($ip)) {
            return null;
        }

        return hash_hmac('sha256', $ip, (string) config('app.key'));
    }

    protected static function trimUserAgent(?string $ua): ?string
    {
        if (empty($ua)) {
            return null;
        }

        return mb_substr($ua, 0, 500);
    }
}
