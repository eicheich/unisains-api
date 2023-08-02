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
     * @param  \Closure  $next
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = $request->user();

        // Get the 'course' parameter from the route (assuming it's a string)
        $course = $request->route('id');

        // Check if the user has bought the course
        $myCourse = $user->myCourses()->where('course_id', $course)->first();
        if (!$myCourse) {
            return response()->json([
                'message' => 'You have not purchased this course',
            ], 403);
        }

        return $next($request);
    }
}
