<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyBread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DailyBreadController extends Controller
{
    public function index(): JsonResponse
    {
        $entries = DailyBread::orderByDesc('published_date')
            ->paginate(15);

        return response()->json($entries);
    }

    public function today(): JsonResponse
    {
        $entry = DailyBread::whereDate('published_date', today())
            ->where('is_published', true)
            ->first();

        if (! $entry) {
            return response()->json(['message' => 'No daily bread for today.'], 404);
        }

        return response()->json($entry);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'bible_reference' => ['nullable', 'string', 'max:255'],
            'published_date' => ['required', 'date', 'unique:daily_breads,published_date'],
            'scheduled_sent_at' => ['nullable', 'date'],
            'is_published' => ['sometimes', 'boolean'],
            'created_by' => ['nullable', 'exists:users,id'],
            'image_url' => ['nullable', 'url'],
        ]);

        $entry = DailyBread::create($validated);

        return response()->json($entry, 201);
    }

    public function show(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);

        return response()->json($entry);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'body' => ['sometimes', 'string'],
            'bible_reference' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published_date' => [
                'sometimes',
                'date',
                Rule::unique('daily_breads', 'published_date')->ignore($entry->id),
            ],
            'scheduled_sent_at' => ['sometimes', 'nullable', 'date'],
            'is_published' => ['sometimes', 'boolean'],
            'created_by' => ['sometimes', 'nullable', 'exists:users,id'],
            'image_url' => ['sometimes', 'nullable', 'url'],
        ]);

        $entry->update($validated);

        return response()->json($entry);
    }

    public function destroy(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);
        $entry->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}