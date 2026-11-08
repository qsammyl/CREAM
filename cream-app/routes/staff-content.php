<?php

/*
|--------------------------------------------------------------------------
| Staff articles & comments routes (loaded inside the /staff group)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Staff\ArticleController;
use App\Http\Controllers\Staff\CommentController;
use App\Http\Controllers\Staff\EnquiryController;
use App\Http\Controllers\Staff\TestimonialController;
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

// Testimonials & feedback moderation
Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::get('testimonials/qr', [TestimonialController::class, 'qr'])->name('testimonials.qr');
Route::post('testimonials/bulk', [TestimonialController::class, 'bulk'])->name('testimonials.bulk');
Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
Route::post('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
Route::post('testimonials/{testimonial}/archive', [TestimonialController::class, 'archive'])->name('testimonials.archive');
Route::post('testimonials/{testimonial}/spam', [TestimonialController::class, 'spam'])->name('testimonials.spam');
Route::post('testimonials/{testimonial}/feature', [TestimonialController::class, 'feature'])->name('testimonials.feature');
Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

// Enquiries inbox
Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
Route::post('enquiries/bulk', [EnquiryController::class, 'bulk'])->name('enquiries.bulk');
Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
Route::patch('enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.status');
Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
