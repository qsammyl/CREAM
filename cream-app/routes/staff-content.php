<?php

/*
|--------------------------------------------------------------------------
| Staff articles & comments routes (loaded inside the /staff group)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Staff\ArticleController;
use App\Http\Controllers\Staff\CommentController;
use Illuminate\Support\Facades\Route;

// Articles
Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
Route::put('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');

// Comment moderation
Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
Route::post('comments/bulk', [CommentController::class, 'bulk'])->name('comments.bulk');
Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
Route::post('comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
