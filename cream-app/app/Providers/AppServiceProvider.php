<?php

namespace App\Providers;

use App\Enums\AssetCategory;
use App\Models\Asset;
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
