<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyBread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyBreadController extends Controller
{
    /**
     * List all daily bread entries, latest first.
     */
    public function index(): JsonResponse
    {
        $entries = DailyBread::latest()->paginate(15);
        return response()->json($entries);
    }

    /**
     * Get today's daily bread.
     */
    public function today(): JsonResponse
    {
        $entry = DailyBread::whereDate('date', today())->first();

        if (!$entry) {
            return response()->json(['message' => 'No daily bread for today.'], 404);
        }

        return response()->json($entry);
    }

    /**
     * Store a new daily bread entry.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'verse'       => 'nullable|string|max:255',
            'verse_text'  => 'nullable|string',
            'date'        => 'required|date|unique:daily_breads,date',
            'author_id'   => 'nullable|exists:users,id',
            'image_url'   => 'nullable|url',
        ]);

        $entry = DailyBread::create($validated);

        return response()->json($entry, 201);
    }

    /**
     * Show a specific daily bread entry.
     */
    public function show(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);
        return response()->json($entry);
    }

    /**
     * Update a daily bread entry.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'content'     => 'sometimes|string',
            'verse'       => 'nullable|string|max:255',
            'verse_text'  => 'nullable|string',
            'date'        => 'sometimes|date|unique:daily_breads,date,' . $entry->id,
            'author_id'   => 'nullable|exists:users,id',
            'image_url'   => 'nullable|url',
        ]);

        $entry->update($validated);

        return response()->json($entry);
    }

    /**
     * Delete a daily bread entry.
     */
    public function destroy(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);
        $entry->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
