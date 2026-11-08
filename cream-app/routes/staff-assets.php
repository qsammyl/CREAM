<?php

/*
|--------------------------------------------------------------------------
| Staff asset management routes (loaded inside the /staff group)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Staff\AssetController;
use App\Http\Controllers\Staff\AssetMediaController;
use Illuminate\Support\Facades\Route;

Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
Route::get('assets/bin', [AssetController::class, 'bin'])->name('assets.bin');
Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
Route::put('assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
Route::post('assets/{asset}/duplicate', [AssetController::class, 'duplicate'])->name('assets.duplicate');
Route::get('assets/{asset}/preview', [AssetController::class, 'preview'])->name('assets.preview');

Route::post('assets/{id}/restore', [AssetController::class, 'restore'])->name('assets.restore');
Route::delete('assets/{id}/force', [AssetController::class, 'forceDelete'])->name('assets.force-delete');

// Media
Route::post('assets/{asset}/media', [AssetMediaController::class, 'store'])->name('assets.media.store');
Route::patch('media/{media}/alt', [AssetMediaController::class, 'updateAlt'])->name('assets.media.alt');
Route::post('media/{media}/cover', [AssetMediaController::class, 'cover'])->name('assets.media.cover');
Route::post('media/{media}/move/{dir}', [AssetMediaController::class, 'move'])
    ->whereIn('dir', ['up', 'down'])->name('assets.media.move');
Route::delete('media/{media}', [AssetMediaController::class, 'destroy'])->name('assets.media.destroy');
