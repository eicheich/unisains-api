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
        $course = Course::with(['category', 'modules', 'summary_modules', 'ars'])
            ->find($id);

        $quizzez = DB::table('quizzes')
            ->where('course_id', $id)
            ->first();

        if ($quizzez) {
            $questions = Question::with('answers')
                ->where('quiz_id', $quizzez->id)
                ->inRandomOrder() // Mengambil pertanyaan secara acak
                ->limit(5) // Mengambil 5 pertanyaan
                ->get();

            if ($course) {
                return response()->json([
                    'course' => $course,
                    'questions' => $questions, // Mengubah 'quizzez' menjadi 'questions'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Course not found'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Quizzes not found'
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


}
