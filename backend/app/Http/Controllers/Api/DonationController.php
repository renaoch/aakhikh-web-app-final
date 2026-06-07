<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class DonationController extends Controller
{
    private Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * POST /api/donations/order
     * Creates a Razorpay order and a pending Donation record.
     */
    public function createOrder(StoreDonationRequest $request)
    {
        $data = $request->validated();

        $razorpayOrder = $this->razorpay->order->create([
            'amount'          => (int) ($data['amount'] * 100), // paise
            'currency'        => 'INR',
            'receipt'         => 'don_' . uniqid(),
            'payment_capture' => 1,
            'notes'           => [
                'category' => $data['category'],
                'name'     => $data['name'],
                'email'    => $data['email'],
            ],
        ]);

        $donation = Donation::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'amount'            => $data['amount'],
            'category'          => $data['category'],
            'razorpay_order_id' => $razorpayOrder->id,
            'status'            => 'pending',
            'user_id'           => $request->attributes->get('auth_user')?->id,
        ]);

        return response()->json([
            'success'          => true,
            'razorpay_order_id'=> $razorpayOrder->id,
            'amount'           => $razorpayOrder->amount,
            'currency'         => $razorpayOrder->currency,
            'donation_id'      => $donation->id,
            'key'              => config('services.razorpay.key'),
        ]);
    }

    /**
     * POST /api/donations/verify
     * Verifies Razorpay payment signature after frontend payment.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'donation_id'         => 'required|integer|exists:donations,id',
        ]);

        try {
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (SignatureVerificationError $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed.',
            ], 422);
        }

        $donation = Donation::findOrFail($request->donation_id);
        $donation->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature'  => $request->razorpay_signature,
            'status'              => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'donation'=> $donation,
        ]);
    }

    /**
     * POST /api/donations/webhook
     * Razorpay webhook — handles payment.captured / payment.failed events.
     * Route must be CSRF-exempt (listed in VerifyCsrfToken / excluded via routes/webhooks.php).
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload   = $request->getContent();

        $expected = hash_hmac('sha256', $payload, config('services.razorpay.webhook_secret'));

        if (! hash_equals($expected, (string) $signature)) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        $event = $request->input('event');
        $paymentEntity = $request->input('payload.payment.entity', []);

        if ($event === 'payment.captured') {
            $orderId = $paymentEntity['order_id'] ?? null;
            if ($orderId) {
                Donation::where('razorpay_order_id', $orderId)
                    ->where('status', 'pending')
                    ->update([
                        'razorpay_payment_id' => $paymentEntity['id'] ?? null,
                        'status'              => 'completed',
                    ]);
            }
        }

        if ($event === 'payment.failed') {
            $orderId = $paymentEntity['order_id'] ?? null;
            if ($orderId) {
                Donation::where('razorpay_order_id', $orderId)
                    ->where('status', 'pending')
                    ->update(['status' => 'failed']);
            }
        }

        return response()->json(['success' => true]);
    }
}
