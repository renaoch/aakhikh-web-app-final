<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sermon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SermonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sermons = Sermon::query()
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%")
                ->orWhere('speaker', 'like', "%{$s}%"))
            ->when($request->featured, fn($q) => $q->where('is_featured', true))
            ->orderBy('published_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $sermons]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'youtube_video_id' => 'required|string|max:20|unique:sermons,youtube_video_id',
            'title'            => 'required|string|max:255',
            'speaker'          => 'required|string|max:100',
            'topic'            => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'thumbnail_url'    => 'nullable|url',
            'published_at'     => 'nullable|date',
            'is_featured'      => 'boolean',
        ]);

        $sermon = Sermon::create($validated);

        return response()->json(['success' => true, 'data' => $sermon], 201);
    }

    public function show(string $id): JsonResponse
    {
        $sermon = Sermon::findOrFail($id);
        return response()->json(['success' => true, 'data' => $sermon]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $sermon = Sermon::findOrFail($id);

        $validated = $request->validate([
            'youtube_video_id' => 'sometimes|string|max:20|unique:sermons,youtube_video_id,' . $sermon->id,
            'title'            => 'sometimes|string|max:255',
            'speaker'          => 'sometimes|string|max:100',
            'topic'            => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'thumbnail_url'    => 'nullable|url',
            'published_at'     => 'nullable|date',
            'is_featured'      => 'boolean',
        ]);

        $sermon->update($validated);

        return response()->json(['success' => true, 'data' => $sermon]);
    }

    public function destroy(string $id): JsonResponse
    {
        $sermon = Sermon::findOrFail($id);
        $sermon->delete();

        return response()->json(['success' => true, 'message' => 'Sermon deleted.']);
    }
}
