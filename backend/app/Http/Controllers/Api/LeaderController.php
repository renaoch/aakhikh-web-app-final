<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaderController extends Controller
{
    public function index(): JsonResponse
    {
        $leaders = Leader::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json($leaders);
    }

    public function show(Leader $leader): JsonResponse
    {
        return response()->json($leader);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo_url' => ['nullable', 'url'],
            'email' => ['nullable', 'email', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'category' => ['sometimes', 'string'],
        ]);

        $leader = Leader::create($validated);

        return response()->json($leader, 201);
    }

    public function update(Request $request, Leader $leader): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'role_title' => ['sometimes', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'photo_url' => ['sometimes', 'nullable', 'url'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'display_order' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'category' => ['sometimes', 'string'],
        ]);

        $leader->update($validated);

        return response()->json($leader);
    }

    public function destroy(Leader $leader): JsonResponse
    {
        $leader->delete();

        return response()->json(['message' => 'Leader deleted successfully.']);
    }
}