<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // store course
    public function store(Request $request)
    {
        // Validate data request
        $validator = Validator::make($request->all(), [
            'title_course' => 'required',
            'description_course' => 'required',
            'is_paid' => 'required|boolean',
            'category_id' => 'required|integer',
            'price' => 'required_if:is_paid,1|numeric|min:0',
            'discount' => 'required_if:is_paid,1|numeric|min:0|max:100',
            'certificate_course' => 'required|file|mimes:pdf,jpeg,png|max:2048',
            'image_course' => 'required|file|mimes:jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Process request
        $certificate = $request->file('certificate_course');
        $certificate_name = time() . '.' . $certificate->getClientOriginalExtension();
        $certificate->move(public_path('storage/images/certificate'), $certificate_name);

        $image = $request->file('image_course');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/images/thumbnail_course'), $image_name);

        $course = Course::create([
            'title_course' => $request->title_course,
            'description' => $request->description_course,
            'is_paid' => $request->is_paid,
            'certificate_course' => $certificate_name,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
        ]);

        if ($request->is_paid == 1 && $request->discount !== null ){
            $price = $request->price;
            $discount = $request->discount;
            DB::table('courses')->where('id', $course->id)->update([
                'price' => $price,
                'discount' => $discount,
            ]);
        } elseif ($request->is_paid == 1 && $request->discount == null) {
            $price = $request->price;
            DB::table('courses')->where('id', $course->id)->update([
                'price' => $price,
            ]);
        }

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
        ], 201);
    }

    public function all()
    {
        $courses = Course::with('category')->paginate(9);
        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Get all courses successfully',
            'courses' => $courses,
        ], 200);

    }

    public function show($id)
    {
        $course = Course::with('category')->find($id);
        if (!$course) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Get course successfully',
            'course' => $course,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate data request
        $validator = Validator::make($request->all(), [
            'title_course' => 'required',
            'description_course' => 'required',
            'is_paid' => 'required|boolean',
            'category_id' => 'required|integer',
            'price' => 'required_if:is_paid,1|numeric|min:0',
            'discount' => 'required_if:is_paid,1|numeric|min:0|max:100',
            'certificate_course' => 'file|mimes:pdf,jpeg,png|max:2048',
            'image_course' => 'file|mimes:jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Process request
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        if ($request->hasFile('certificate_course')) {
            $certificate = $request->file('certificate_course');
            $certificate_name = time() . '.' . $certificate->getClientOriginalExtension();
            $certificate->move(public_path('storage/images/certificate'), $certificate_name);
            Storage::delete('public/images/certificate/' . $course->certificate_course);
        } else {
            $certificate_name = $course->certificate_course;
        }

        if ($request->hasFile('image_course')) {
            $image = $request->file('image_course');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images/thumbnail_course'), $image_name);
            Storage::delete('public/images/thumbnail_course/' . $course->image_course);
        } else {
            $image_name = $course->image_course;
        }

        $course->update([
            'title_course' => $request->title_course,
            'description' => $request->description_course,
            'is_paid' => $request->is_paid,
            'certificate_course' => $certificate_name,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
        ]);

        if ($request->is_paid == 1 && $request->discount !== null ){
            $price = $request->price;
            $discount = $request->discount;
            DB::table('courses')->where('id', $course->id)->update([
                'price' => $price,
                'discount' => $discount,
            ]);
        } elseif ($request->is_paid == 1 && $request->discount == null) {
            $price = $request->price;
            DB::table('courses')->where('id', $course->id)->update([
                'price' => $price,
            ]);
        }

    }
    public function delete($id)
    {
        $course = Course::withTrashed()->find($id);
        if (!$course) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        $course->delete();
        return response()->json([
            'message' => 'Course deleted successfully',
        ], 200);
    }

}
