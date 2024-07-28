<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $request->user();
        $allowedRoles = explode('|', $roles);

        // Check if the user's role is in the allowed roles or if the user has the role_id of 5
        if (in_array($user->role_id, $allowedRoles) || $user->role_id == 5) {
            return $next($request);
        }

        return response()->json([
            'message' => "Not Allowed",
        ], 403);
    }
}
