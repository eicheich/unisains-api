<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function all()
    {
        $courses = DB::table('courses')->get();
        if ($courses) {
            return response()->json([
                'data' => $courses
            ], 200);
        }
        return response()->json([
            'message' => 'data not found'
        ], 404);
    }

    public function category()
    {
        $anatomi = DB::table('courses')->where('category_id', 1)->get();
        $astronomi = DB::table('courses')->where('category_id', 2)->get();

        if ($anatomi->isEmpty() && $astronomi->isEmpty()) {
            return response()->json([
                'message' => 'data not found',
            ], 404);
        } else
            return response()->json([
                'anatomi' => $anatomi,
                'astronomi' => $astronomi,
            ],200);

    }
}