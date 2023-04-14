<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'message' => 'Unauthorized. Token not found.',
            ], 401);
        }

        $token = explode(' ', $request->header('Authorization'))[1];

        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthorized. Invalid token or user is not an teacher.',
            ], 401);
        }

        $user = Auth::guard('sanctum')->user();
        if (!$user || $user->role != 'teacher') {
            return response()->json([
                'message' => 'Unauthorized. Invalid token or user is not an teacher.',
            ], 401);
        }

        return $next($request);
    }
}