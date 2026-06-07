<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 'approved')
            ->latest('submitted_at')
            ->get();

        return response()->json($testimonials);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'content'     => 'required|string|max:2000',
        ]);

        $testimonial = Testimonial::create([
            ...$validated,
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json($testimonial, 201);
    }
}