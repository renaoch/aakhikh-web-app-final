<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DailyBreadController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\EmailLogController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LeaderController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\MinistryTeamController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SermonController;
use App\Http\Controllers\Api\ServiceScheduleController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
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

Route::get('announcements',               [AnnouncementController::class, 'index']);
Route::get('announcements/{announcement}',[AnnouncementController::class, 'show']);

Route::get('testimonials', [TestimonialController::class, 'index']);

Route::get('leaders',          [LeaderController::class, 'index']);
Route::get('leaders/{leader}', [LeaderController::class, 'show']);

Route::get('ministry-teams',                [MinistryTeamController::class, 'index']);
Route::get('ministry-teams/{ministryTeam}', [MinistryTeamController::class, 'show']);

Route::get('products',           [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('service-schedules', [ServiceScheduleController::class, 'index']);
Route::get('site-settings',     [SiteSettingController::class, 'index']);

// Donations
Route::post('donations/create-order', [DonationController::class, 'createOrder']);
Route::post('donations/verify',       [DonationController::class, 'verify']);

// Orders (guest checkout)
Route::post('orders',        [OrderController::class, 'store']);
Route::post('orders/verify', [OrderController::class, 'verify']);
Route::get('orders/{order}', [OrderController::class, 'show']);

// Newsletter
Route::post('subscribe',          [SubscriberController::class, 'subscribe']);
Route::get('unsubscribe/{token}', [SubscriberController::class, 'unsubscribe']);

// Webhooks
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

    // Media
    Route::post('media/upload', [MediaController::class, 'upload']);
    Route::delete('media',      [MediaController::class, 'delete']);

    /*--- Admin + Editor ---*/
    Route::middleware('role:admin,editor')->group(function () {

        Route::post('sermons',            [SermonController::class, 'store']);
        Route::put('sermons/{sermon}',    [SermonController::class, 'update']);
        Route::delete('sermons/{sermon}', [SermonController::class, 'destroy']);

        Route::post('daily-breads',                [DailyBreadController::class, 'store']);
        Route::put('daily-breads/{dailyBread}',    [DailyBreadController::class, 'update']);
        Route::delete('daily-breads/{dailyBread}', [DailyBreadController::class, 'destroy']);

        Route::post('events',           [EventController::class, 'store']);
        Route::put('events/{event}',    [EventController::class, 'update']);
        Route::delete('events/{event}', [EventController::class, 'destroy']);

        Route::post('announcements',                 [AnnouncementController::class, 'store']);
        Route::put('announcements/{announcement}',   [AnnouncementController::class, 'update']);
        Route::delete('announcements/{announcement}',[AnnouncementController::class, 'destroy']);

        Route::get('testimonials/pending',               [TestimonialController::class, 'pending']);
        Route::post('testimonials',                      [TestimonialController::class, 'store']);
        Route::put('testimonials/{testimonial}',         [TestimonialController::class, 'update']);
        Route::patch('testimonials/{testimonial}/approve',[TestimonialController::class, 'approve']);
        Route::delete('testimonials/{testimonial}',      [TestimonialController::class, 'destroy']);

        Route::post('leaders',            [LeaderController::class, 'store']);
        Route::put('leaders/{leader}',    [LeaderController::class, 'update']);
        Route::delete('leaders/{leader}', [LeaderController::class, 'destroy']);

        Route::post('ministry-teams',                  [MinistryTeamController::class, 'store']);
        Route::put('ministry-teams/{ministryTeam}',    [MinistryTeamController::class, 'update']);
        Route::delete('ministry-teams/{ministryTeam}', [MinistryTeamController::class, 'destroy']);

        Route::apiResource('ministry-teams.members', TeamMemberController::class);

        Route::post('products',            [ProductController::class, 'store']);
        Route::put('products/{product}',   [ProductController::class, 'update']);
        Route::delete('products/{product}',[ProductController::class, 'destroy']);

        Route::get('orders',               [OrderController::class, 'index']);
        Route::get('donations',            [DonationController::class, 'index']);
        Route::get('donations/{donation}', [DonationController::class, 'show']);

        Route::get('subscribers',                  [SubscriberController::class, 'index']);
        Route::delete('subscribers/{subscriber}',  [SubscriberController::class, 'destroy']);

        Route::get('email-logs', [EmailLogController::class, 'index']);

        Route::post('service-schedules',                    [ServiceScheduleController::class, 'store']);
        Route::put('service-schedules/{serviceSchedule}',   [ServiceScheduleController::class, 'update']);
        Route::delete('service-schedules/{serviceSchedule}',[ServiceScheduleController::class, 'destroy']);

        Route::put('site-settings', [SiteSettingController::class, 'update']);
    });

    /*--- Admin only ---*/
    Route::middleware('role:admin')->group(function () {
        Route::get('users',               [UserController::class, 'index']);
        Route::get('users/{user}',        [UserController::class, 'show']);
        Route::put('users/{user}',        [UserController::class, 'update']);
        Route::patch('users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('users/{user}',     [UserController::class, 'destroy']);
    });
});
