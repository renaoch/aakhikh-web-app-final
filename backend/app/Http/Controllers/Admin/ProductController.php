<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->in_stock, fn($q) => $q->where('stock_qty', '>', 0))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $products]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock_qty'   => 'required|integer|min:0',
            'image_url'   => 'nullable|url',
            'category'    => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $product = Product::create($validated);

        return response()->json(['success' => true, 'data' => $product], 201);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        return response()->json(['success' => true, 'data' => $product]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'stock_qty'   => 'sometimes|integer|min:0',
            'image_url'   => 'nullable|url',
            'category'    => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $product->update($validated);

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted.']);
    }
}
