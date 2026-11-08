<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AssetMedia extends Model
{
    protected $table = 'asset_media';

    protected $fillable = [
        'asset_id', 'disk', 'path', 'path_sm', 'original_name',
        'alt', 'is_cover', 'sort_order', 'width', 'height',
    ];

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
            'sort_order' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function urlSm(): ?string
    {
        return $this->path_sm ? Storage::disk($this->disk)->url($this->path_sm) : null;
    }

    /** srcset string for the two widths. */
    public function srcset(): string
    {
        $parts = [];
        if ($this->path_sm) {
            $parts[] = $this->urlSm().' 800w';
        }
        $parts[] = $this->url().' 1600w';

        return implode(', ', $parts);
    }
}
