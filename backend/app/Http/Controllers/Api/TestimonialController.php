<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * List all approved testimonials (public).
     */
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::where('is_approved', true)
            ->latest()
            ->get();

        return response()->json($testimonials);
    }

    /**
     * List ALL testimonials including pending (admin).
     */
    public function pending(): JsonResponse
    {
        $testimonials = Testimonial::where('is_approved', false)
            ->latest()
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Show a single testimonial.
     */
    public function show(Testimonial $testimonial): JsonResponse
    {
        return response()->json($testimonial);
    }

    /**
     * Submit a new testimonial (public — starts as unapproved).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'author_name'  => 'required|string|max:255',
            'author_photo' => 'nullable|url',
            'content'      => 'required|string|max:2000',
            'role'         => 'nullable|string|max:100',
        ]);

        $testimonial = Testimonial::create([
            ...$validated,
            'is_approved' => false, // always starts pending
        ]);

        return response()->json([
            'message' => 'Testimonial submitted and pending review.',
            'data'    => $testimonial,
        ], 201);
    }

    /**
     * Approve a testimonial (admin).
     */
    public function approve(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_approved' => true]);
        return response()->json(['message' => 'Testimonial approved.', 'data' => $testimonial]);
    }

    /**
     * Update a testimonial (admin).
     */
    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validated = $request->validate([
            'author_name'  => 'sometimes|string|max:255',
            'author_photo' => 'nullable|url',
            'content'      => 'sometimes|string|max:2000',
            'role'         => 'nullable|string|max:100',
            'is_approved'  => 'boolean',
        ]);

        $testimonial->update($validated);

        return response()->json($testimonial);
    }

    /**
     * Delete a testimonial (admin).
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();
        return response()->json(['message' => 'Testimonial deleted successfully.']);
    }
}
