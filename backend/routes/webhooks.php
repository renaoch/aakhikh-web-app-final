<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonationController;

Route::post('/razorpay', [DonationController::class, 'webhook']);

Route::post('/ses', function () {
    return response()->json(['success' => true, 'message' => 'SES webhook received.']);
});

Route::post('/sns', function () {
    return response()->json(['success' => true, 'message' => 'SNS webhook received.']);
});