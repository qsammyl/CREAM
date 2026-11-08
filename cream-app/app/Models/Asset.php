<?php

namespace App\Models;

use App\Enums\AssetCategory;
use App\Enums\AssetStatus;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug', 'reference', 'title', 'summary', 'summary2', 'description',
        'category', 'status',
        'is_exclusive', 'is_featured', 'is_off_plan', 'is_furnished', 'title_verified',
        'verified_summary', 'documents_available',
        'price', 'currency', 'price_period', 'price_on_request', 'price_note',
        'beds', 'baths', 'floor_area', 'plot_size', 'tenure', 'survey', 'access',
        'lease_years', 'stage', 'expected_delivery', 'availability', 'max_guests',
        'location', 'location_area', 'location_city', 'country', 'area_key',
        'latitude', 'longitude', 'features', 'video_url', 'sort_order',
        'published_at', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'category' => AssetCategory::class,
            'status' => AssetStatus::class,
            'currency' => Currency::class,
            'is_exclusive' => 'boolean',
            'is_featured' => 'boolean',
            'is_off_plan' => 'boolean',
            'is_furnished' => 'boolean',
            'title_verified' => 'boolean',
            'price_on_request' => 'boolean',
            'documents_available' => 'array',
            'features' => 'array',
            'price' => 'integer',
            'max_guests' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'published_at' => 'datetime',
        ];
    }

    /* ---------------- Relations ---------------- */

    public function media(): HasMany
    {
        return $this->hasMany(AssetMedia::class)->orderByDesc('is_cover')->orderBy('sort_order');
    }

    public function redirects(): HasMany
    {
        return $this->hasMany(AssetRedirect::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function cover(): ?AssetMedia
    {
        return $this->media->firstWhere('is_cover', true) ?? $this->media->first();
    }

    /* ---------------- Scopes ---------------- */

    /** Visible on the public site at all. */
    public function scopePublic(Builder $q): Builder
    {
        return $q->whereIn('status', [
            AssetStatus::Published->value,
            AssetStatus::UnderOffer->value,
            AssetStatus::Sold->value,
            AssetStatus::Let->value,
        ]);
    }

    /** Shown in the default grids (sold/let excluded). */
    public function scopeDefaultListings(Builder $q): Builder
    {
        return $q->whereIn('status', [
            AssetStatus::Published->value,
            AssetStatus::UnderOffer->value,
        ]);
    }

    public function scopeCategory(Builder $q, AssetCategory|string $category): Builder
    {
        return $q->where('category', $category instanceof AssetCategory ? $category->value : $category);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeExclusive(Builder $q): Builder
    {
        return $q->where('is_exclusive', true);
    }

    /* ---------------- Helpers ---------------- */

    public function isLand(): bool
    {
        return $this->category === AssetCategory::Land;
    }

    public function isPriceOnRequest(): bool
    {
        return $this->price_on_request || $this->price === null;
    }

    /** Plain formatted price, e.g. "USD 420,000 / month". */
    public function formattedPrice(): string
    {
        if ($this->isPriceOnRequest()) {
            return 'Price on request';
        }

        $out = $this->currency->value.' '.number_format((int) $this->price);

        return match ($this->price_period) {
            'month' => $out.' / month',
            'night' => $out.' / night',
            default => $out,
        };
    }

    public function statusBadge(): ?string
    {
        return $this->status->publicBadge();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------------- Model events ---------------- */

    protected static function booted(): void
    {
        static::creating(function (Asset $asset) {
            if (blank($asset->slug)) {
                $asset->slug = static::uniqueSlug($asset->title);
            }
            if (blank($asset->reference)) {
                $asset->reference = static::generateReference($asset->title);
            }
        });

        static::updating(function (Asset $asset) {
            // A slug change leaves a 301 breadcrumb from the old URL.
            if ($asset->isDirty('slug')) {
                $original = $asset->getOriginal('slug');
                if (! blank($original) && $original !== $asset->slug) {
                    AssetRedirect::query()->updateOrCreate(
                        ['old_slug' => $original],
                        ['asset_id' => $asset->id, 'created_at' => now()],
                    );
                    // If the new slug was itself a previous old_slug, drop that stale redirect.
                    AssetRedirect::query()->where('old_slug', $asset->slug)->delete();
                }
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'asset';
        $slug = $base;
        $i = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->withTrashed()
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public static function generateReference(string $title): string
    {
        $initials = collect(preg_split('/\s+/', trim($title)))
            ->filter()
            ->map(fn ($w) => strtoupper($w[0]))
            ->take(2)
            ->implode('');
        $initials = $initials ?: 'XX';

        $n = 1;
        do {
            $ref = sprintf('CR-%s-%02d', $initials, $n);
            $n++;
        } while (static::query()->where('reference', $ref)->withTrashed()->exists());

        return $ref;
    }
}
