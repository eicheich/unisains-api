<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function add()
    {
        $kategori = Category::all();
        return view('admin.course.add', compact('kategori'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_course' => 'required',
            'description' => 'required',
            'is_paid' => 'required|boolean',
            'category_id' => 'required|integer',
            'certificate_course' => 'required|file|mimes:pdf,jpeg,png',
            'image_course' => 'required|file|mimes:jpeg,png',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }

        $certificate = $request->file('certificate_course');
        $certificate_name = time() . '.' . $certificate->getClientOriginalExtension();
        $certificate->move(public_path('storage/images/certificate'), $certificate_name);

        $image = $request->file('image_course');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/images/thumbnail_course'), $image_name);
        $course = Course::create([
            'title_course' => $request->title_course,
            'description' => $request->description,
            'is_paid' => $request->is_paid,
            'certificate_course' => $certificate_name,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
        ]);

        if ($request->is_paid == 1 && $request->discount !== null) {
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

        return redirect()->route('course.page')->with('status', 'course telah di tambahkan');
    }
    public function all()
    {
        $courses = Course::with('category')->paginate(9);
        return view('admin.course.course', compact('courses'));
    }
    public function show($id)
    {
        $course = Course::with('category')->find($id);
        $quiz = DB::table('quizzes')->where('course_id', $id)->get();
        $modules = DB::table('modules')->where('course_id', $id)->get();
        $module_rangkuman = DB::table('module_rangkuman')->where('course_id', $id)->get();
        $ar = DB::table('augmented_realities')->where('course_id', $id)->get();
        return view('admin.course.show', compact('course', 'modules', 'module_rangkuman','ar','quiz'));

    }
    public function updatePage($id)
    {
        $course = Course::all()->find($id);
        $category = Category::all();
        return view('admin.course.edit', compact('course', 'category'));
    }
    public function update(Request $request, $id)
    {
        // Validate data request
        $validator = Validator::make($request->all(), [
            'title_course' => 'required',
            'description' => 'required',
            'is_paid' => 'required|boolean',
            'category_id' => 'required|integer',
            'certificate_course' => 'nullable|file|mimes:pdf,jpeg,png   ',
            'image_course' => 'nullable|file|mimes:jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }
        $course = Course::find($id);
        $image = $request->file('image_course');
        if ($request->hasFile('image_course')) {
            $old_image = public_path('storage/images/thumbnail_course/') . $course->image_course;
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $image = $request->file('image_course');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images/thumbnail_course'), $image_name);
        } else {
            $image_name = $course->image_course;
        }


        $certificate = $request->file('certificate_course');
        if ($request->hasFile('certificate_course')) {
            $old_image = public_path('storage/images/certificate/') . $course->certificate_course;
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $certificate = $request->file('certificate_course');
            $certificate_name = time() . '.' . $certificate->getClientOriginalExtension();
            $certificate->move(public_path('storage/images/certificate'), $certificate_name);

        } else {
            $certificate_name = $course->certificate_course;
        }

        $course->update([
            'title_course' => $request->title_course,
            'description' => $request->description,
            'is_paid' => $request->is_paid,
            'certificate_course' => $certificate_name,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
        ]);
        if ($request->is_paid == 1 && $request->discount !== null) {
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
        return redirect()->route('course.page')->with('status', 'course telah di update');

    }
    public function delete($id)
    {
        $course = Course::findorfail($id);
        $old_certificate = public_path('storage/images/certificate/') . $course->certificate_course;
        unlink($old_certificate);
        if ($course) {
            $old_image = public_path('storage/images/thumbnail_course/') . $course->image_course;
            if (file_exists($old_image && $old_certificate)) {
                unlink($old_image);

            }
            $course->delete();
        }
        return redirect()->route('course.page')->with('status', 'course telah di hapus');
    }
}
