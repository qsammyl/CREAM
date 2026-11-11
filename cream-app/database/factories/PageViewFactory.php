<?php

namespace Database\Factories;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageView>
 */
class PageViewFactory extends Factory
{
    protected $model = PageView::class;

    public function definition(): array
    {
        return [
            'path' => fake()->randomElement(['/', '/about', '/properties', '/properties/buy', '/contact', '/short-stays']),
            'visitor_hash' => hash('sha256', (string) fake()->unique()->uuid()),
            'referer_host' => null,
            'utm_source' => null,
            'country' => null,
            'asset_id' => null,
            'created_at' => now(),
        ];
    }

    public function on(\DateTimeInterface $when): static
    {
        return $this->state(['created_at' => $when]);
    }

    public function path(string $path): static
    {
        return $this->state(['path' => $path]);
    }

    /** All views share one visitor fingerprint (for unique-visitor assertions). */
    public function visitor(string $hash): static
    {
        return $this->state(['visitor_hash' => $hash]);
    }
}
