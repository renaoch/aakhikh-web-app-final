<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sermon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SermonController extends Controller
{
    public function index(Request $request)
    {
        $sermons = Cache::remember('sermon_list_latest', now()->addHours(2), function () use ($request) {
            return Sermon::query()
                ->when($request->speaker, fn($q) => $q->where('speaker', $request->speaker))
                ->when($request->topic,   fn($q) => $q->where('topic', $request->topic))
                ->orderByDesc('published_at')
                ->paginate(12);
        });

        return response()->json($sermons);
    }

    public function featured()
    {
        $sermon = Cache::remember('sermon_featured', now()->addHour(), function () {
            return Sermon::where('is_featured', true)->latest('published_at')->first();
        });

        return response()->json($sermon);
    }

    public function show(Sermon $sermon)
    {
        return response()->json($sermon);
    }
}