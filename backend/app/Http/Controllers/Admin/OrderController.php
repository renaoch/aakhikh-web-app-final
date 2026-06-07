<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with(['user', 'items.product'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")
            ))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'total_amount'     => 'required|numeric|min:0',
            'status'           => 'required|string|in:pending,paid,shipped,delivered,cancelled',
            'payment_id'       => 'nullable|string',
            'shipping_address' => 'nullable|array',
        ]);

        $order = Order::create($validated);

        return response()->json(['success' => true, 'data' => $order->load('items')], 201);
    }

    public function show(string $id): JsonResponse
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $order]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status'           => 'sometimes|string|in:pending,paid,shipped,delivered,cancelled',
            'payment_id'       => 'nullable|string',
            'shipping_address' => 'nullable|array',
            'total_amount'     => 'sometimes|numeric|min:0',
        ]);

        $order->update($validated);

        return response()->json(['success' => true, 'data' => $order->load('items.product')]);
    }

    public function destroy(string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted.']);
    }
}
