<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Announcement::query();

        if (! $request->boolean('all')) {
            $now = now();

            $query->where('is_active', true)
                ->where('published_at', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', $now);
                });
        }

        $announcements = $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($announcements);
    }

    public function show(Announcement $announcement): JsonResponse
    {
        return response()->json($announcement);
    }

    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $announcement = Announcement::create($request->validated());

        return response()->json($announcement, 201);
    }

    public function update(StoreAnnouncementRequest $request, Announcement $announcement): JsonResponse
    {
        $announcement->update($request->validated());

        return response()->json($announcement);
    }

    public function destroy(Announcement $announcement): JsonResponse
    {
        $announcement->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}