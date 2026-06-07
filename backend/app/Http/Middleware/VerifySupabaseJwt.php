<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySupabaseJwt
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors'  => (object) [],
            ], 401);
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('supabase.jwt_secret'), 'HS256')
            );

            // Pull role from metadata
            $role = $decoded->user_metadata->role
                ?? $decoded->app_metadata->role
                ?? 'member';

            // Sync user to local users table
            $user = User::firstOrCreate(
                ['supabase_uid' => $decoded->sub],
                [
                    'email' => $decoded->email ?? '',
                    'name'  => $decoded->user_metadata?->full_name
                                ?? $decoded->user_metadata?->name
                                ?? 'Member',
                    'role'  => $role,
                ]
            );

            // Keep role in sync if it changed in Supabase metadata
            if (($user->role instanceof \BackedEnum ? $user->role->value : $user->role) !== $role) {
                $user->update(['role' => $role]);
            }

            // Attach to request
            $request->setUserResolver(fn() => $user);
            $request->attributes->set('auth_user',    $user);
            $request->attributes->set('auth_user_id', $decoded->sub);
            $request->attributes->set('auth_email',   $decoded->email ?? null);
            $request->attributes->set('auth_role',    $user->role instanceof \BackedEnum
                ? $user->role->value
                : $user->role
            );

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
                'errors'  => (object) [],
            ], 401);
        }

        return $next($request);
    }
}