<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Support\Facades\Cache;

class LeaderController extends Controller
{
    public function index()
    {
        $leaders = Cache::remember('leaders_all', now()->addHour(), function () {
            return Leader::orderBy('display_order')->get();
        });

        return response()->json($leaders);
    }
}