<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class OrderController extends Controller
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
     * POST /api/orders
     * Creates Razorpay order + local Order + OrderItems.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        // Calculate total from DB prices (never trust client)
        $total = 0;
        $itemsData = [];
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        DB::beginTransaction();
        try {
            $razorpayOrder = $this->razorpay->order->create([
                'amount'          => (int) ($total * 100),
                'currency'        => 'INR',
                'receipt'         => 'ord_' . uniqid(),
                'payment_capture' => 1,
            ]);

            $order = Order::create([
                'user_id'           => $request->attributes->get('auth_user')?->id,
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'] ?? null,
                'address'           => $data['address'] ?? null,
                'total_amount'      => $total,
                'razorpay_order_id' => $razorpayOrder->id,
                'status'            => 'pending',
            ]);

            foreach ($itemsData as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Order creation failed: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'           => true,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount'            => $razorpayOrder->amount,
            'currency'          => $razorpayOrder->currency,
            'order_id'          => $order->id,
            'key'               => config('services.razorpay.key'),
        ], 201);
    }

    /**
     * POST /api/orders/verify
     * Verifies Razorpay signature and marks order as paid.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'order_id'            => 'required|integer|exists:orders,id',
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

        $order = Order::with('items.product')->findOrFail($request->order_id);
        $order->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature'  => $request->razorpay_signature,
            'status'              => 'paid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order payment verified.',
            'order'   => $order,
        ]);
    }

    /**
     * GET /api/orders/{id}
     * Get a single order (must belong to authenticated user).
     */
    public function show(Request $request, int $id)
    {
        $user  = $request->attributes->get('auth_user');
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $order]);
    }
}
