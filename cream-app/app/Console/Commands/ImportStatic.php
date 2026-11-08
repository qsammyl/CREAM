<?php

namespace App\Console\Commands;

use App\Enums\AssetCategory;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\AssetMedia;
use App\Support\ImageProcessor;
use Illuminate\Console\Command;
use Throwable;

class ImportStatic extends Command
{
    protected $signature = 'cream:import-static';

    protected $description = 'Import the static site data (listings & land + photos) into the database. Safe to run more than once — existing records are skipped, not duplicated.';

    /** Slugs the static homepage featured, preserved so the home grid matches. */
    private const HOME_FEATURED = [
        'royal-manhattan', 'beverly-homes', 'salem-plus-homes',
        'cantonments-court', 'airport-residential-loft', 'adjiringanor-homes',
    ];

    public function handle(ImageProcessor $images): int
    {
        // Decoding large original photos (some 10 MB+) through GD needs headroom.
        @ini_set('memory_limit', '512M');

        $jsonPath = base_path('../data/listings.json');
        if (! is_file($jsonPath)) {
            $this->error("listings.json not found at {$jsonPath}");

            return self::FAILURE;
        }

        $data = json_decode((string) file_get_contents($jsonPath), true);
        if (! is_array($data)) {
            $this->error('listings.json could not be parsed.');

            return self::FAILURE;
        }

        $items = array_merge($data['listings'] ?? [], $data['land'] ?? []);

        $created = 0;
        $skipped = 0;
        $photosDone = 0;
        $photosMissing = 0;

        foreach ($items as $l) {
            $ref = $l['ref'] ?? null;

            if ($ref && Asset::query()->where('reference', $ref)->withTrashed()->exists()) {
                $this->line("  skip (already imported): {$l['title']} [{$ref}]");
                $skipped++;

                continue;
            }

            $asset = Asset::create($this->mapFields($l));
            $created++;
            $this->info("  + {$asset->title} [{$asset->reference}] ({$asset->category->value})");

            foreach (($l['photos'] ?? []) as $i => $rel) {
                $srcFull = base_path('../'.ltrim($rel, '/'));
                if (! is_file($srcFull)) {
                    $this->warn("    photo missing on disk: {$rel}");
                    $photosMissing++;

                    continue;
                }

                try {
                    // Trusted local originals — bypass the 10 MB upload ceiling.
                    $res = $images->process($srcFull, maxBytes: null);
                    AssetMedia::create([
                        'asset_id' => $asset->id,
                        'disk' => 'uploads',
                        'path' => $res['path'],
                        'path_sm' => $res['path_sm'],
                        'original_name' => basename($rel),
                        'alt' => $asset->title,
                        'is_cover' => $i === 0,
                        'sort_order' => $i,
                        'width' => $res['width'],
                        'height' => $res['height'],
                    ]);
                    $photosDone++;
                    $this->line("    photo -> {$res['path']}");
                } catch (Throwable $e) {
                    $this->warn("    photo failed ({$rel}): {$e->getMessage()}");
                    $photosMissing++;
                }
            }
        }

        $this->newLine();
        $this->info("Done. Imported {$created} asset(s); skipped {$skipped} already present. Photos: {$photosDone} processed, {$photosMissing} missing/failed.");

        if ($photosMissing > 0) {
            $this->comment('Missing photos are expected where the static site used branded placeholders (TODO(sam) in data/listings.json).');
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $l
     * @return array<string, mixed>
     */
    protected function mapFields(array $l): array
    {
        $tags = $l['tags'] ?? [];
        $status = $l['status'] ?? 'sale';

        $category = match ($status) {
            'rent' => AssetCategory::Rent,
            'land' => AssetCategory::Land,
            'off-plan' => AssetCategory::NewProject,
            'short-stay' => AssetCategory::ShortStay,
            default => AssetCategory::Sale,
        };

        $blankToNull = fn ($v) => ($v === '' || $v === null) ? null : $v;

        return [
            'slug' => $l['slug'] ?? null,
            'reference' => $l['ref'] ?? null,
            'title' => $l['title'],
            'summary' => $blankToNull($l['summary'] ?? null),
            'summary2' => $blankToNull($l['summary2'] ?? null),
            'category' => $category,
            'status' => AssetStatus::Published,
            'is_exclusive' => in_array('exclusive', $tags, true),
            'is_furnished' => in_array('furnished', $tags, true),
            'is_off_plan' => $status === 'off-plan' || in_array('under-construction', $tags, true),
            'is_featured' => in_array($l['slug'] ?? '', self::HOME_FEATURED, true),
            'title_verified' => (bool) ($l['titleVerified'] ?? false),
            'verified_summary' => $blankToNull($l['verifiedSummary'] ?? null),
            'documents_available' => $l['documents'] ?? null,
            'price' => ($l['price'] ?? '') === '' ? null : (int) $l['price'],
            'currency' => $l['currency'] ?? 'USD',
            'price_period' => $l['pricePeriod'] ?? 'total',
            'price_on_request' => ($l['price'] ?? '') === '',
            'price_note' => $blankToNull($l['priceNote'] ?? null),
            'beds' => ($l['beds'] ?? '') === '' ? null : (string) $l['beds'],
            'baths' => ($l['baths'] ?? '') === '' ? null : (string) $l['baths'],
            'floor_area' => $blankToNull($l['area'] ?? null),
            'plot_size' => $blankToNull($l['plotSize'] ?? null),
            'tenure' => $blankToNull($l['tenure'] ?? null),
            'survey' => $blankToNull($l['survey'] ?? null),
            'access' => $blankToNull($l['access'] ?? null),
            'stage' => $blankToNull($l['stage'] ?? null),
            'expected_delivery' => $blankToNull($l['delivery'] ?? null),
            'availability' => $blankToNull($l['availability'] ?? null),
            'location' => $blankToNull($l['location'] ?? null),
            'area_key' => $blankToNull($l['area_key'] ?? null),
            'features' => $l['features'] ?? null,
            'video_url' => $blankToNull($l['video'] ?? null),
            'published_at' => now(),
        ];
    }
}
