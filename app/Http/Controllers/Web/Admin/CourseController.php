<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Quiz;
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
        $request->validate([
            'title_course' => 'required',
            'description' => 'required',
            'is_paid' => 'required|boolean',
            'category_id' => 'required|integer',
            'image_course' => 'required|file|mimes:jpeg,png',
            'price' => 'nullable|required_if:is_paid,1|numeric',
            'link_chat' => 'required',
        ]);

        $image_name = $this->uploadImage($request->file('image_course'));

        $courseData = [
            'title_course' => $request->title_course,
            'description' => $request->description,
            'is_paid' => $request->is_paid,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
            'course_code' => 'UNI' . rand(1000, 9999),
            'link_chat' => $request->link_chat,
        ];

        if ($request->is_paid == 1) {
            $courseData['price'] = $request->price;
        }

        try {
            DB::beginTransaction();
//            store course
            $course = Course::create($courseData);
            Quiz::create([
                'course_id' => $course->id,
                'title_quiz' => $course->title_course,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('course.page')->with('success', 'Course telah ditambahkan');
    }

    private function uploadImage($image)
    {
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/images/thumbnail_course'), $image_name);
        return $image_name;
    }

    public function all()
    {
        $courses = Course::with('category')->paginate(9);
        return view('admin.course.course', compact('courses'));
    }
    public function show($id)
    {
        $course = Course::with('category')->find($id);
        $quiz = Quiz::with('questions')->where('course_id', $id)->get();
        $modules = DB::table('modules')->where('course_id', $id)->get();
        $summary_modules = DB::table('summary_modules')->where('course_id', $id)->get();
        $ar = DB::table('augmented_realities')->where('course_id', $id)->get();
        return view('admin.course.show', compact('course', 'modules', 'summary_modules', 'ar', 'quiz'));
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
        $course->update([
            'title_course' => $request->title_course,
            'description' => $request->description,
            'is_paid' => $request->is_paid,
            'image_course' => $image_name,
            'category_id' => $request->category_id,
        ]);
 if ($request->is_paid == 1 && $request->discount == null) {
            $price = $request->price;
            DB::table('courses')->where('id', $course->id)->update([
                'price' => $price,
            ]);
        }
        return redirect()->route('course.page')->with('success', 'course telah di update');
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $module = DB::table('modules')->where('course_id', $id)->get();
        $module_rangkuman = DB::table('summary_modules')->where('course_id', $id)->get();
        $quiz = DB::table('quizzes')->where('course_id', $id)->get();
        $ar = DB::table('augmented_realities')->where('course_id', $id)->get();

        try {
            DB::beginTransaction();

            DB::table('modules')->where('course_id', $id)->delete();
            DB::table('summary_modules')->where('course_id', $id)->delete();

            // Menghapus kuis
            DB::table('quizzes')->where('course_id', $id)->delete();

            // Menghapus augmented reality
            DB::table('augmented_realities')->where('course_id', $id)->delete();

            // Menghapus model course secara lunak
            $course->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('course.page')->with('error', $th->getMessage());
        }

        return redirect()->route('course.page')->with('success', 'Kursus telah dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $courses = Course::where('title_course', 'like', '%' . $search . '%')->paginate(9);
        return view('admin.course.course', compact('courses'));

    }
}
