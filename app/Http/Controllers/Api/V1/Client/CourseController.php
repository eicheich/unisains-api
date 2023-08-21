<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Helpers\UrlHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function all()
    {
        $courses = Course::with('category')->where('is_public', 1)->get();
        return response()->json([
                'message' => 'success',
                'data' => [
                    'courses' => $courses,
                ],
            ], 200);
    }


    public function category()
    {
        $anatomiCourses = Course::with('category')->where('category_id', 1)->get();
        $astronomiCourses = Course::with('category')->where('category_id', 2)->get();

        $categories = [
            'anatomi' => $anatomiCourses,
            'astronomi' => $astronomiCourses,
        ];

        $response = [];

        foreach ($categories as $categoryName => $courses) {
            if ($courses->isEmpty()) {
                $response[$categoryName] = 'Course not found';
            } else {
                $response[$categoryName] = $courses;
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $response,
        ], 200);
    }


    public function learn($id)
    {
        $course = Course::with(['category', 'modules','summary_modules', 'ars'])
            ->find($id);
        $quizzez = DB::table('quizzes')
            ->where('course_id', $id)
            ->first();
        $question = Question::with('answers')
            ->where('quiz_id', $quizzez->id)
            ->get();

        if ($course) {
            return response()->json([
                'course' => $course,
                'quizzez' => $question,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
    }
    public function show($id)
    {
        $course = Course::with(['category', 'rates.user','modules' => function ($query) {
            $query->select('course_id', 'title_module', 'description','image_module');
        }])->find($id);
        if ($course) {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'course' => $course,
                ],
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
    }



    public function preview($id)
    {
        $course = Course::with(['category', 'modules', 'rates'])
            ->find($id);
        if ($course) {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'course' => $course,
                ],
            ], 200);
        } else {
            return response()->json([
                'message' => 'course not found',
            ], 404);
        }
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $courses = Course::with(['category','rates'])
            ->where('title_course', 'like', '%' . $search . '%')
            ->get();

//        jika input kosong tampilkan semua
        if ($search == null) {
            $courses = Course::with(['category','rates'])
                ->get();
        }

        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'courses' => $courses,
                ],
            ], 200);
        }
    }

    public function trxquiz(Request $request)
    {

    }

    public function rate(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        } else
            try {
                $rate = DB::table('rates')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'rate' => $request->rate,
                    'comment' => $request->comment,
                    'created_at' => now()
                    ]);
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'rate' => $rate
                    ]
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => $th
                ], 500);
            }
    }
}
