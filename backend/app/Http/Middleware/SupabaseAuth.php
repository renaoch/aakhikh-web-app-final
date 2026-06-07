<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('services.supabase.jwt_secret'), 'HS256')
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        // Find or create the user in local DB synced from Supabase
        $user = User::firstOrCreate(
            ['supabase_id' => $decoded->sub],
            [
                'email'     => $decoded->email ?? '',
                'name'      => $decoded->user_metadata->name ?? 'User',
                'is_active' => true,
            ]
        );

        if (!$user->is_active) {
            return response()->json(['message' => 'Account is disabled.'], 403);
        }

        $user->update(['last_login_at' => now()]);
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
