<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('items.product', 'user')->latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'      => 'required|integer|min:1',
            'shipping_address' => 'required|array',
        ]);

        // TODO: Razorpay order creation + stock check

        return response()->json(['message' => 'Order placement coming soon.']);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('items.product', 'user'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted.']);
    }
}