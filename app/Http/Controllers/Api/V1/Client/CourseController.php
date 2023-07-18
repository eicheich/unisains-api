<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Helpers\UrlHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function all()
    {
        $courses = Course::with('category')->get();

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
            ]);
        }
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
        $course = DB::table('courses')
            ->where('courses.id', $id)
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->select('courses.*', 'categories.name_category')
            ->first();
        $module = DB::table('modules')->where('course_id', $id)->get();
        $module_rangkuman = DB::table('module_rangkuman')->where('course_id', $id)->get();
        $ar = DB::table('augmented_realities')->where('course_id', $id)->first();
        $quiz = DB::table('quizzes')->where('course_id', $id)->get();

        if ($course) {
            // Mengubah URL gambar dan video menjadi URL yang valid
            $course->image_course = UrlHelper::formatImageCourseUrl($course->image_course);
            $course->certificate_course = UrlHelper::formatCertiCourseUrl($course->certificate_course);

            foreach ($module as $moduleItem) {
                $moduleItem->image_module = UrlHelper::formatImageModuleUrl($moduleItem->image_module);
            }

            foreach ($module_rangkuman as $rangkumanItem) {
                $rangkumanItem->video_rangkuman = UrlHelper::formatVideoUrl($rangkumanItem->video_rangkuman);
            }


            if ($ar) {
                $ar->image_ar = asset('storage/images/ar/' . $ar->image_ar);
            }

            return response()->json([
                'course' => $course,
                'module' => $module,
                'module_rangkuman' => $module_rangkuman,
                'ar' => $ar,
                'quiz' => $quiz,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
    }

    public function show($id)
    {
        $course = DB::table('courses')
            ->where('courses.id', $id)
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->select('courses.*', 'categories.name_category')
            ->first();

        $modules = DB::table('modules')
            ->select('title_module', 'description')
            ->where('course_id', $id)
            ->get();

        if ($course) {
            $course->image_course = UrlHelper::formatImageCourseUrl($course->image_course);
            $course->certificate_course = UrlHelper::formatCertiCourseUrl($course->certificate_course);
            return response()->json([
                'course' => $course,
                'modules' => $modules,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
    }



    public function preview($id)
    {
        $courses = DB::table('courses')
            ->where('courses.id', $id)
            ->join('categories', 'courses.category_id', '=', 'categories.id')
//            ->join('modules', 'modules.course_id', '=', 'courses.id')
            ->select('courses.id', 'courses.title_course', 'courses.description', 'courses.price', 'courses.image_course', 'categories.name_category',)
            ->get();

        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'course not found',
            ], 404);
        } else {
            $courses = $courses->map(function ($course) {
                $course->image_course = UrlHelper::formatImageCourseUrl($course->image_course);
                return $course;
            });

            return response()->json([
                'course' => $courses,
            ], 200);
        }
    }


    public function search(Request $request)
    {
        $search = $request->search;
        $course = DB::table('courses')
            ->where('courses.title_course', 'like', '%' . $search . '%')
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->select('courses.id', 'courses.title_course', 'courses.description', 'courses.price', 'courses.image_course', 'categories.name_category')
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
