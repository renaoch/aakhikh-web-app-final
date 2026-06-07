<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'name'  => 'nullable|string|max:255',
        ]);

        $subscriber = Subscriber::create([
            ...$validated,
            'status' => 'unconfirmed',
            'token'  => Str::uuid(),
        ]);

        // TODO: dispatch confirmation email job

        return response()->json(['message' => 'Please check your email to confirm.'], 201);
    }

    public function confirm(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['status' => 'active', 'confirmed_at' => now()]);

        return response()->json(['message' => 'Subscription confirmed!']);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['status' => 'unsubscribed']);

        return response()->json(['message' => 'You have been unsubscribed.']);
    }
}