<?php

use Illuminate\Support\Facades\Route;

// Existing
use App\Http\Controllers\Api\V1\Public\HealthController;
use App\Http\Controllers\Api\V1\Member\ProfileController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;

// Public
use App\Http\Controllers\Api\SermonController;
use App\Http\Controllers\Api\DailyBreadController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LeaderController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\SubscriberController;

// Admin
use App\Http\Controllers\Admin\SermonController as AdminSermonController;
use App\Http\Controllers\Admin\DailyBreadController as AdminDailyBreadController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/health', HealthController::class);

// Sermons
Route::get('sermons',          [SermonController::class, 'index']);
Route::get('sermons/featured', [SermonController::class, 'featured']);
Route::get('sermons/{sermon}', [SermonController::class, 'show']);

// Daily Bread
Route::get('daily-bread/today', [DailyBreadController::class, 'today']);

// Events
Route::get('events',         [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);

// Testimonials
Route::get('testimonials',  [TestimonialController::class, 'index']);
Route::post('testimonials', [TestimonialController::class, 'store']);

// Leaders
Route::get('leaders', [LeaderController::class, 'index']);

// Donations
Route::post('donations/create-order', [DonationController::class, 'createOrder']);
Route::post('donations/verify',       [DonationController::class, 'verify']);
Route::post('webhooks/razorpay',      [DonationController::class, 'webhook']);

// Subscribers
Route::post('subscribe',                [SubscriberController::class, 'subscribe']);
Route::get('subscribe/confirm/{token}', [SubscriberController::class, 'confirm']);
Route::get('unsubscribe/{token}',       [SubscriberController::class, 'unsubscribe']);

/*
|--------------------------------------------------------------------------
| Member Routes — Supabase JWT required
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.supabase'])->group(function () {
    Route::get('/me',           [ProfileController::class, 'show']);
    Route::post('store/orders', [AdminOrderController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes — JWT + role required
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    Route::middleware(['auth.supabase', 'role:super_admin,admin,editor,media'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
    });

    Route::middleware(['auth.supabase', 'role:super_admin,admin,editor'])->group(function () {
        Route::apiResource('sermons',     AdminSermonController::class);
        Route::apiResource('daily-bread', AdminDailyBreadController::class);
        Route::put('testimonials/{testimonial}', [AdminTestimonialController::class, 'update']);
    });

    Route::middleware(['auth.supabase', 'role:super_admin,admin'])->group(function () {
        Route::apiResource('announcements', AdminAnnouncementController::class);
        Route::apiResource('products',      AdminProductController::class);
        Route::apiResource('orders',        AdminOrderController::class);
        Route::post('email/announce',       [AdminAnnouncementController::class, 'sendEmail']);
        Route::get('users',                 [AdminUserController::class, 'index']);
    });

    Route::middleware(['auth.supabase', 'role:super_admin,admin,media'])->group(function () {
        Route::post('media/upload', [AdminMediaController::class, 'upload']);
    });

    Route::middleware(['auth.supabase', 'role:super_admin'])->group(function () {
        Route::put('users/{user}/role', [AdminUserController::class, 'updateRole']);
        Route::delete('users/{user}',   [AdminUserController::class, 'destroy']);
    });
});