<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'amount'   => 'required|numeric|min:1',
            'category' => 'required|in:tithe,mission,general',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
        ]);

        // TODO: integrate Razorpay SDK to create order
        // $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        // $order = $api->order->create(['amount' => $validated['amount'] * 100, 'currency' => 'INR']);

        return response()->json(['message' => 'Razorpay order creation coming soon.']);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $expectedSignature = hash_hmac(
            'sha256',
            $validated['razorpay_order_id'] . '|' . $validated['razorpay_payment_id'],
            config('services.razorpay.secret')
        );

        if ($expectedSignature !== $validated['razorpay_signature']) {
            return response()->json(['message' => 'Invalid signature.'], 422);
        }

        // TODO: update donation record as verified

        return response()->json(['message' => 'Payment verified.']);
    }

    public function webhook(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload   = $request->getContent();
        $expected  = hash_hmac('sha256', $payload, config('services.razorpay.webhook_secret'));

        if ($signature !== $expected) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        // TODO: handle payment.captured event

        return response()->json(['message' => 'Webhook received.']);
    }
}