<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyBread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DailyBreadController extends Controller
{
    public function index()
    {
        return response()->json(DailyBread::latest('published_date')->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'body'           => 'required|string',
            'bible_reference'=> 'nullable|string|max:255',
            'image_url'      => 'nullable|url',
            'published_date' => 'required|date|unique:daily_breads,published_date',
        ]);

        $dailyBread = DailyBread::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        Cache::forget('daily_bread_today');

        return response()->json($dailyBread, 201);
    }

    public function show(DailyBread $dailyBread)
    {
        return response()->json($dailyBread);
    }

    public function update(Request $request, DailyBread $dailyBread)
    {
        $validated = $request->validate([
            'title'          => 'sometimes|string|max:255',
            'body'           => 'sometimes|string',
            'bible_reference'=> 'nullable|string|max:255',
            'image_url'      => 'nullable|url',
            'published_date' => 'sometimes|date',
        ]);

        $dailyBread->update($validated);
        Cache::forget('daily_bread_today');

        return response()->json($dailyBread);
    }

    public function destroy(DailyBread $dailyBread)
    {
        $dailyBread->delete();
        Cache::forget('daily_bread_today');

        return response()->json(['message' => 'Devotional deleted.']);
    }
}