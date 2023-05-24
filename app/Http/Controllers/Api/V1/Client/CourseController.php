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
        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'course not found',
            ], 404);
        } else
            return response()->json([
                'courses' => $courses,
            ], 200);
    }

    public function category()
    {
        $anatomi = DB::table('courses')->where('category_id', 1)->get();
        $astronomi = DB::table('courses')->where('category_id', 2)->get();

        if ($anatomi->isEmpty() && $astronomi->isEmpty()) {
            return response()->json([
                'message' => 'course not found',
            ], 404);
        } else
            return response()->json([
                'anatomi' => $anatomi,
                'astronomi' => $astronomi,
            ], 200);
    }

    public function show($id)
    {
        $course = DB::table('courses')->where('id', $id)->first();
        if ($course) {
            return response()->json([
                'course' => $course
            ], 200);
        } else
            return response()->json([
                'message' => 'course not found'
            ], 404);
    }

    public function preview($id)
    {
        $course = DB::table('courses')->where('id', $id)
            ->select('courses.id', 'courses.title_course', 'courses.description', 'courses.price', 'courses.image_course')
            // ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->get();

        if ($course->isEmpty()) {
            return response()->json([
                'message' => 'course not found',
            ], 404);
        } else
            return response()->json([
                'course' => $course,
            ], 200);
    }
}
