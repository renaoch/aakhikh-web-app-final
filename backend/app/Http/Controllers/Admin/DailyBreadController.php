<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyBread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyBreadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $entries = DailyBread::query()
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->date, fn($q, $d) => $q->whereDate('scheduled_date', $d))
            ->orderBy('scheduled_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $entries]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'verse'          => 'required|string',
            'verse_ref'      => 'required|string|max:100',
            'content'        => 'required|string',
            'scheduled_date' => 'required|date|unique:daily_breads,scheduled_date',
            'author'         => 'nullable|string|max:100',
        ]);

        $entry = DailyBread::create($validated);

        return response()->json(['success' => true, 'data' => $entry], 201);
    }

    public function show(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);
        return response()->json(['success' => true, 'data' => $entry]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);

        $validated = $request->validate([
            'title'          => 'sometimes|string|max:255',
            'verse'          => 'sometimes|string',
            'verse_ref'      => 'sometimes|string|max:100',
            'content'        => 'sometimes|string',
            'scheduled_date' => 'sometimes|date|unique:daily_breads,scheduled_date,' . $entry->id,
            'author'         => 'nullable|string|max:100',
        ]);

        $entry->update($validated);

        return response()->json(['success' => true, 'data' => $entry]);
    }

    public function destroy(string $id): JsonResponse
    {
        $entry = DailyBread::findOrFail($id);
        $entry->delete();

        return response()->json(['success' => true, 'message' => 'Daily bread deleted.']);
    }
}
