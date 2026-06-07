<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    public function index()
    {
        $events = Cache::remember('events_upcoming', now()->addMinutes(30), function () {
            return Event::where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->get();
        });

        return response()->json($events);
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }
}