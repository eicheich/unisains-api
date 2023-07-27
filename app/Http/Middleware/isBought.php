<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isBought
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $course = $request->route('course');
        $myCourse = $user->myCourses()->where('course_id', $course->id)->first();
        if (!$myCourse) {
            return response()->json([
                'message' => 'You have not bought this course',
            ], 403);
        }
        return $next($request);
    }
}
