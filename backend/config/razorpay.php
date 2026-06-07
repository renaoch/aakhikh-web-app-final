<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Razorpay
    |--------------------------------------------------------------------------
    | Set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in your .env file.
    | RAZORPAY_WEBHOOK_SECRET should match the webhook secret set in
    | the Razorpay dashboard.
    */

    'key_id'         => env('RAZORPAY_KEY_ID'),
    'key_secret'     => env('RAZORPAY_KEY_SECRET'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
];
