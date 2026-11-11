<?php

namespace App\Providers;

use App\Enums\AssetCategory;
use App\Models\Asset;
use App\Support\SiteSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Overlay admin-edited settings onto config('cream.*') defaults.
        SiteSettings::apply();

        // Public comment submissions: 3 per IP per 10 minutes.
        RateLimiter::for('comments', fn ($request) => Limit::perMinutes(10, 3)->by($request->ip()));

        // Public feedback / testimonial submissions: 3 per IP per 30 minutes.
        RateLimiter::for('testimonials', fn ($request) => Limit::perMinutes(30, 3)->by($request->ip()));

        // Public enquiry forms: 6 per IP per 10 minutes.
        RateLimiter::for('enquiries', fn ($request) => Limit::perMinutes(10, 6)->by($request->ip()));

        // The new-projects promo popup is driven by the live new-project asset.
        View::composer('public.partials.popup', function ($view) {
            $view->with('popupAsset', once(fn () => Asset::query()
                ->defaultListings()
                ->category(AssetCategory::NewProject)
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at')
                ->first()));
        });
    }
}
