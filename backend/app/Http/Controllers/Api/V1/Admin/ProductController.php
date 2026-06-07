<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock_qty'   => 'required|integer|min:0',
            'images'      => 'nullable|array',
            'is_active'   => 'boolean',
            'category'    => 'nullable|string|max:255',
        ]);

        return response()->json(Product::create($validated), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'stock_qty'   => 'sometimes|integer|min:0',
            'images'      => 'nullable|array',
            'is_active'   => 'boolean',
            'category'    => 'nullable|string|max:255',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }
}