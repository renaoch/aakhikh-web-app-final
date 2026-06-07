<?php

use App\Enums\UserRole;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'role:super_admin,admin,editor,media'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::middleware('role:super_admin,admin')->group(function () {
            // users, products, announcements, subscribers, donations reports
        });

        Route::middleware('role:super_admin,admin,editor')->group(function () {
            // sermons, daily breads, events, testimonials moderation
        });

        Route::middleware('role:super_admin,admin,media')->group(function () {
            // media upload, youtube sync
        });

        Route::middleware('role:super_admin')->group(function () {
            // role changes, hard delete users, system settings destructive ops
        });
    });