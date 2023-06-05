<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Helpers\UrlHelper;
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
                'message' => 'Course not found',
            ], 404);
        } else {
            // Mengubah setiap item kursus untuk menyertakan URL gambar
            $courses = $courses->map(function ($course) {
                $course->image_course = asset('storage/images/thumbnail_course/' . $course->image_course);
                $course->certificate_course = asset('storage/images/certificate/' . $course->certificate_course);
                return $course;
            });

            return response()->json([
                'courses' => $courses,
            ], 200);
        }
    }

    public function category()
    {
        $anatomi = DB::table('courses')->where('category_id', 1)->get();
        $astronomi = DB::table('courses')->where('category_id', 2)->get();

        if ($anatomi->isEmpty() && $astronomi->isEmpty()) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        } else {
            // Mengubah setiap item kursus untuk menyertakan URL gambar
            $anatomi = $anatomi->map(function ($course) {
                $course->image_course = asset('storage/images/thumbnail_course/' . $course->image_course);
                $course->certificate_course = asset('storage/images/certificate/' . $course->certificate_course);
                return $course;
            });

            $astronomi = $astronomi->map(function ($course) {
                $course->image_course = asset('storage/images/thumbnail_course/' . $course->image_course);
                $course->certificate_course = asset('storage/images/certificate/' . $course->certificate_course);
                return $course;
            });

            return response()->json([
                'anatomi' => $anatomi,
                'astronomi' => $astronomi,
            ], 200);
        }
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
        $course = DB::table('courses')
            ->where('courses.id', $id)
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            // join module and select the module course_id
            ->join('modules', 'courses.id', '=', 'modules.course_id')
            ->select('courses.id', 'courses.title_course', 'courses.description', 'courses.price', 'courses.image_course', 'categories.name_category',)
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
