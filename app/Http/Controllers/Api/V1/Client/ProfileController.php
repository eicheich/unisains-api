<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $my_course = DB::table('my_course')
            ->join('courses', 'courseS.id', '=', 'my_course.course_id')
            ->where('my_course.user_id', $user->id)
            ->first();
            if ($my_course == null) {
                return response()->json([
                    'user' => $user,
                    'message' => 'You have not purchased any course',
                ], 200);
            } else
        return response()->json([
            'user' => $user,
            'my_course' => $my_course,
        ], 200);
    }
}
