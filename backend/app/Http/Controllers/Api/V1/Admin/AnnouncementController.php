<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return response()->json(Announcement::latest('published_at')->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($announcement, 201);
    }

    public function show(Announcement $announcement)
    {
        return response()->json($announcement);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'body'         => 'sometimes|string',
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update($validated);

        return response()->json($announcement);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted.']);
    }

    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        // TODO: dispatch SendAnnouncementEmail job

        return response()->json(['message' => 'Announcement email queued.']);
    }
}