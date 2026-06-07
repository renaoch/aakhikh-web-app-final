<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sermon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SermonController extends Controller
{
    public function index()
    {
        return response()->json(Sermon::latest('published_at')->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'youtube_video_id' => 'required|string|unique:sermons',
            'title'            => 'required|string|max:255',
            'speaker'          => 'required|string|max:255',
            'topic'            => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'thumbnail_url'    => 'nullable|url',
            'published_at'     => 'required|date',
            'is_featured'      => 'boolean',
        ]);

        $sermon = Sermon::create($validated);
        Cache::forget('sermon_list_latest');
        Cache::forget('sermon_featured');

        return response()->json($sermon, 201);
    }

    public function show(Sermon $sermon)
    {
        return response()->json($sermon);
    }

    public function update(Request $request, Sermon $sermon)
    {
        $validated = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'speaker'       => 'sometimes|string|max:255',
            'topic'         => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'thumbnail_url' => 'nullable|url',
            'published_at'  => 'sometimes|date',
            'is_featured'   => 'boolean',
        ]);

        $sermon->update($validated);
        Cache::forget('sermon_list_latest');
        Cache::forget('sermon_featured');

        return response()->json($sermon);
    }

    public function destroy(Sermon $sermon)
    {
        $sermon->delete();
        Cache::forget('sermon_list_latest');
        Cache::forget('sermon_featured');

        return response()->json(['message' => 'Sermon deleted.']);
    }
}