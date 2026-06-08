<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::where('status', 'approved')
            ->latest()
            ->get();

        return response()->json($testimonials);
    }

    public function pending(): JsonResponse
    {
        $testimonials = Testimonial::where('status', 'pending')
            ->latest()
            ->get();

        return response()->json($testimonials);
    }

    public function show(Testimonial $testimonial): JsonResponse
    {
        return response()->json($testimonial);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'submitted_by' => ['nullable', 'uuid'],
        ]);

        $testimonial = Testimonial::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Testimonial submitted and pending review.',
            'data' => $testimonial,
        ], 201);
    }

    public function approve(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Testimonial approved.',
            'data' => $testimonial->fresh(),
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validated = $request->validate([
            'author_name' => ['sometimes', 'string', 'max:255'],
            'author_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'content' => ['sometimes', 'string', 'max:2000'],
            'submitted_by' => ['sometimes', 'nullable', 'uuid'],
            'reviewed_by' => ['sometimes', 'nullable', 'uuid'],
            'reviewed_at' => ['sometimes', 'nullable', 'date'],
            'rejection_note' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected'],
        ]);

        $testimonial->update($validated);

        return response()->json($testimonial);
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted successfully.']);
    }
}