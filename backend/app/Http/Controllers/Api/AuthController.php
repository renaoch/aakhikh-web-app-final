<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Return the currently authenticated user (resolved via SupabaseAuth middleware).
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
