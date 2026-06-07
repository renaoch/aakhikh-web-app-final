<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyBreadController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MinistryTeamController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SermonController;
use App\Http\Controllers\ServiceScheduleController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

// Auth
Route::prefix('auth')->group(function () {
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Public CMS reads
Route::get('sermons',           [SermonController::class, 'index']);
Route::get('sermons/{sermon}',  [SermonController::class, 'show']);

Route::get('daily-breads',              [DailyBreadController::class, 'index']);
Route::get('daily-breads/today',        [DailyBreadController::class, 'today']);
Route::get('daily-breads/{dailyBread}', [DailyBreadController::class, 'show']);

Route::get('events',         [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);

Route::get('announcements',              [AnnouncementController::class, 'index']);
Route::get('announcements/{announcement}', [AnnouncementController::class, 'show']);

Route::get('testimonials',              [TestimonialController::class, 'index']);

Route::get('leaders',          [LeaderController::class, 'index']);
Route::get('leaders/{leader}', [LeaderController::class, 'show']);

Route::get('ministry-teams',                  [MinistryTeamController::class, 'index']);
Route::get('ministry-teams/{ministryTeam}',   [MinistryTeamController::class, 'show']);

Route::get('products',           [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('service-schedules',  [ServiceScheduleController::class, 'index']);
Route::get('site-settings',      [SiteSettingController::class, 'index']);

// Donations (public — user provides email)
Route::post('donations/create-order', [DonationController::class, 'createOrder']);
Route::post('donations/verify',       [DonationController::class, 'verify']);

// Orders (public — guest checkout)
Route::post('orders',          [OrderController::class, 'store']);
Route::post('orders/verify',   [OrderController::class, 'verify']);
Route::get('orders/{order}',   [OrderController::class, 'show']);

// Newsletter
Route::post('subscribe',               [SubscriberController::class, 'subscribe']);
Route::get('unsubscribe/{token}',      [SubscriberController::class, 'unsubscribe']);

// Webhooks (verified internally via signature)
Route::post('webhooks/razorpay', [WebhookController::class, 'razorpay']);
Route::post('webhooks/ses',      [WebhookController::class, 'ses']);

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Media upload/delete
    Route::post('media/upload', [MediaController::class, 'upload']);
    Route::delete('media',      [MediaController::class, 'delete']);

    /*
    |----------------------------------------------------------------------
    | Admin / Editor routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,editor')->group(function () {

        // Sermons
        Route::post('sermons',            [SermonController::class, 'store']);
        Route::put('sermons/{sermon}',    [SermonController::class, 'update']);
        Route::delete('sermons/{sermon}', [SermonController::class, 'destroy']);

        // Daily Bread
        Route::post('daily-breads',               [DailyBreadController::class, 'store']);
        Route::put('daily-breads/{dailyBread}',   [DailyBreadController::class, 'update']);
        Route::delete('daily-breads/{dailyBread}',[DailyBreadController::class, 'destroy']);

        // Events
        Route::post('events',            [EventController::class, 'store']);
        Route::put('events/{event}',     [EventController::class, 'update']);
        Route::delete('events/{event}',  [EventController::class, 'destroy']);

        // Announcements
        Route::post('announcements',                [AnnouncementController::class, 'store']);
        Route::put('announcements/{announcement}',  [AnnouncementController::class, 'update']);
        Route::delete('announcements/{announcement}',[AnnouncementController::class, 'destroy']);

        // Testimonials
        Route::get('testimonials/pending',              [TestimonialController::class, 'pending']);
        Route::post('testimonials',                     [TestimonialController::class, 'store']);
        Route::put('testimonials/{testimonial}',        [TestimonialController::class, 'update']);
        Route::patch('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve']);
        Route::delete('testimonials/{testimonial}',     [TestimonialController::class, 'destroy']);

        // Leaders
        Route::post('leaders',            [LeaderController::class, 'store']);
        Route::put('leaders/{leader}',    [LeaderController::class, 'update']);
        Route::delete('leaders/{leader}', [LeaderController::class, 'destroy']);

        // Ministry Teams
        Route::post('ministry-teams',                   [MinistryTeamController::class, 'store']);
        Route::put('ministry-teams/{ministryTeam}',     [MinistryTeamController::class, 'update']);
        Route::delete('ministry-teams/{ministryTeam}',  [MinistryTeamController::class, 'destroy']);

        // Team Members
        Route::apiResource('ministry-teams.members', \App\Http\Controllers\TeamMemberController::class);

        // Products
        Route::post('products',            [ProductController::class, 'store']);
        Route::put('products/{product}',   [ProductController::class, 'update']);
        Route::delete('products/{product}',[ProductController::class, 'destroy']);

        // Orders (admin reads)
        Route::get('orders',               [OrderController::class, 'index']);

        // Donations (admin reads)
        Route::get('donations',            [DonationController::class, 'index']);
        Route::get('donations/{donation}', [DonationController::class, 'show']);

        // Subscribers
        Route::get('subscribers',                    [SubscriberController::class, 'index']);
        Route::delete('subscribers/{subscriber}',    [SubscriberController::class, 'destroy']);

        // Email Logs
        Route::get('email-logs', [\App\Http\Controllers\EmailLogController::class, 'index']);

        // Service Schedules
        Route::post('service-schedules',               [ServiceScheduleController::class, 'store']);
        Route::put('service-schedules/{serviceSchedule}', [ServiceScheduleController::class, 'update']);
        Route::delete('service-schedules/{serviceSchedule}', [ServiceScheduleController::class, 'destroy']);

        // Site Settings
        Route::put('site-settings', [SiteSettingController::class, 'update']);
    });

    /*
    |----------------------------------------------------------------------
    | Admin-only routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('users',                   [UserController::class, 'index']);
        Route::get('users/{user}',            [UserController::class, 'show']);
        Route::put('users/{user}',            [UserController::class, 'update']);
        Route::patch('users/{user}/role',     [UserController::class, 'updateRole']);
        Route::delete('users/{user}',         [UserController::class, 'destroy']);
    });

});
