<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $testimonials = Testimonial::query()
            ->when($request->approved, fn($q) => $q->where('is_approved', true))
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $testimonials]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'is_approved' => 'required|boolean',
            'name'        => 'sometimes|string|max:100',
            'content'     => 'sometimes|string',
            'is_featured' => 'boolean',
        ]);

        $testimonial->update($validated);

        return response()->json(['success' => true, 'data' => $testimonial]);
    }

    public function destroy(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json(['success' => true, 'message' => 'Testimonial deleted.']);
    }
}
