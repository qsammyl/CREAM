<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Staff\ActivityController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ProfileController;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site (database-driven, clean URLs — unchanged from the static site)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

Route::get('/properties', [PublicController::class, 'properties'])->name('properties');
Route::get('/properties/buy', [PublicController::class, 'buy'])->name('properties.buy');
Route::get('/properties/rent', [PublicController::class, 'rent'])->name('properties.rent');
Route::get('/properties/land', [PublicController::class, 'land'])->name('properties.land');

Route::get('/sell', [PublicController::class, 'sell'])->name('sell');
Route::get('/exclusives', [PublicController::class, 'exclusives'])->name('exclusives');
Route::get('/new-projects', [PublicController::class, 'newProjects'])->name('new-projects');
Route::get('/short-stays', [PublicController::class, 'shortStays'])->name('short-stays');
Route::get('/search-document', [PublicController::class, 'searchDocument'])->name('search-document');

Route::get('/insights', [PublicController::class, 'insights'])->name('insights');
Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PublicController::class, 'blogPost'])->where('slug', '[a-z0-9-]+')->name('blog.show');
Route::post('/blog/{slug}/comments', [PublicController::class, 'storeComment'])
    ->where('slug', '[a-z0-9-]+')->middleware('throttle:comments')->name('comments.store');

Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');

Route::get('/listings/{slug}', [PublicController::class, 'listing'])
    ->where('slug', '[a-z0-9-]+')->name('listings.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [SitemapController::class, 'robots']);

// Hand-written legacy blog posts keep their original top-level URLs.
Route::get('/{legacy}', [PublicController::class, 'legacyArticle'])
    ->where('legacy', 'blog-[a-z0-9-]+')->name('blog.legacy');

/*
|--------------------------------------------------------------------------
| Staff dashboard (/staff)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active', AuthenticateSession::class, '2fa.admin'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::post('/profile/logout-other-sessions', [ProfileController::class, 'logoutOtherSessions'])
            ->name('profile.logout-others');

        Route::middleware('admin')->group(function () {
            Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
        });

        require __DIR__.'/staff-assets.php';
        require __DIR__.'/staff-content.php';
    });
