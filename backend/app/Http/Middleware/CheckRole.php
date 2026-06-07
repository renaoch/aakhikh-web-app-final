<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = $request->attributes->get('auth_role', 'member');

        if (! in_array($userRole, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this resource.',
                'errors'  => (object) [],
            ], 403);
        }

        return $next($request);
    }
}