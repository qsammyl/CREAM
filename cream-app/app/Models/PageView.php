<?php

namespace App\Models;

use Database\Factories\PageViewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single public page view. Append-only, cookieless: there is no updated_at,
 * no raw IP and no cookie — repeat visitors within a day collapse onto one
 * daily-rotating `visitor_hash` (see App\Support\Analytics).
 */
class PageView extends Model
{
    /** @use HasFactory<PageViewFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'path', 'visitor_hash', 'referer_host', 'utm_source', 'country', 'asset_id', 'created_at',
    ];

    protected $hidden = ['visitor_hash'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
