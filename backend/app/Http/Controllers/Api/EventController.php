<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    /**
     * List upcoming events (public, cached 30 min).
     */
    public function index(): JsonResponse
    {
        $events = Cache::remember('events_upcoming', now()->addMinutes(30), function () {
            return Event::where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->get();
        });

        return response()->json($events);
    }

    /**
     * List ALL events including past ones (admin use).
     */
    public function all(): JsonResponse
    {
        $events = Event::orderByDesc('starts_at')->paginate(20);
        return response()->json($events);
    }

    /**
     * Show a single event.
     */
    public function show(Event $event): JsonResponse
    {
        return response()->json($event);
    }

    /**
     * Create a new event.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'starts_at'     => 'required|date',
            'ends_at'       => 'nullable|date|after:starts_at',
            'image_url'     => 'nullable|url',
            'is_featured'   => 'boolean',
            'registration_url' => 'nullable|url',
        ]);

        $event = Event::create($validated);

        Cache::forget('events_upcoming');

        return response()->json($event, 201);
    }

    /**
     * Update an event.
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'starts_at'     => 'sometimes|date',
            'ends_at'       => 'nullable|date|after:starts_at',
            'image_url'     => 'nullable|url',
            'is_featured'   => 'boolean',
            'registration_url' => 'nullable|url',
        ]);

        $event->update($validated);

        Cache::forget('events_upcoming');

        return response()->json($event);
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        Cache::forget('events_upcoming');

        return response()->json(['message' => 'Event deleted successfully.']);
    }
}
