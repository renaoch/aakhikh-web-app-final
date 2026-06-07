<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $testimonial->update([
            'status'      => $validated['status'],
            'reviewed_by' => $request->user()->id,
        ]);

        return response()->json($testimonial);
    }
}