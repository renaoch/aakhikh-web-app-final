<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderController extends Controller
{
    /**
     * List all leaders, ordered by display order.
     */
    public function index(): JsonResponse
    {
        $leaders = Leader::orderBy('order')->get();
        return response()->json($leaders);
    }

    /**
     * Show a single leader.
     */
    public function show(Leader $leader): JsonResponse
    {
        return response()->json($leader);
    }

    /**
     * Create a new leader.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'bio'         => 'nullable|string',
            'photo_url'   => 'nullable|url',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $leader = Leader::create($validated);

        return response()->json($leader, 201);
    }

    /**
     * Update a leader.
     */
    public function update(Request $request, Leader $leader): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'title'       => 'sometimes|string|max:255',
            'bio'         => 'nullable|string',
            'photo_url'   => 'nullable|url',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $leader->update($validated);

        return response()->json($leader);
    }

    /**
     * Delete a leader.
     */
    public function destroy(Leader $leader): JsonResponse
    {
        $leader->delete();
        return response()->json(['message' => 'Leader deleted successfully.']);
    }
}
