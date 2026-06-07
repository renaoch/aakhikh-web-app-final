<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AnnouncementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $announcements = Announcement::query()
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $announcements]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'type'       => 'required|string|in:general,event,urgent',
            'expires_at' => 'nullable|date|after:now',
            'is_active'  => 'boolean',
        ]);

        $announcement = Announcement::create($validated);

        return response()->json(['success' => true, 'data' => $announcement], 201);
    }

    public function show(string $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        return response()->json(['success' => true, 'data' => $announcement]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title'      => 'sometimes|string|max:255',
            'body'       => 'sometimes|string',
            'type'       => 'sometimes|string|in:general,event,urgent',
            'expires_at' => 'nullable|date|after:now',
            'is_active'  => 'boolean',
        ]);

        $announcement->update($validated);

        return response()->json(['success' => true, 'data' => $announcement]);
    }

    public function destroy(string $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted.']);
    }

    public function sendEmail(Request $request): JsonResponse
    {
        $request->validate([
            'announcement_id' => 'required|exists:announcements,id',
        ]);

        $announcement = Announcement::findOrFail($request->announcement_id);
        $subscribers  = Subscriber::where('is_active', true)->get();

        foreach ($subscribers as $subscriber) {
            \App\Jobs\SendAnnouncementEmail::dispatch($subscriber, $announcement);
        }

        return response()->json([
            'success' => true,
            'message' => "Email queued for {$subscribers->count()} subscribers.",
        ]);
    }
}
