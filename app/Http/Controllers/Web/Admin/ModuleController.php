<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_module' => 'required|string|max:255',
            'materi_module' => 'required',
            'description' => 'required',
            'image_module' => 'required|file|mimes:jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('warning', $validator->errors());
        }

        $image = $request->file('image_module');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/images/module'), $image_name);

        $module = Module::create([
            'title_module' => $request->title_module,
            'materi_module' => $request->materi_module,
            'description' => $request->description,
            'image_module' => $image_name,
            'course_id' => $request->course_id,
        ]);

        return redirect()->back()->with('success', 'Module created successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title_module' => 'required|string|max:255',
            'materi_module' => 'required|string',
            'description' => 'required|string',
            'image_module' => 'nullable|file|mimes:jpeg,png|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }
        $module = Module::findorfail($id);
        $image = $request->file('image_module');
        if ($request->hasFile('image_module')) {
            $old_image = public_path('storage/images/module/') . $module->image_module;
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $image = $request->file('image_module');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images/module'), $image_name);
        } else {
            $image_name = $module->image_module;
        }
        $module->update([
            'title_module' => $request->title_module,
            'materi_module' => $request->materi_module,
            'description' => $request->description,
            'image_module' => $image_name,
        ]);
        return redirect()->route('course.page');
    }
    public function updatePage($id)
    {
        $module = Module::where('id', $id)->first();
        return view('admin.course.module.update', compact('module'));
    }
    public function delete($id)
    {
        $module = Module::findorfail($id);
        if ($module) {
            $old_image = public_path('storage/images/module/') . $module->image_module;
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $module->delete();
        }
        return redirect()->back()->with('status', 'Module deleted successfully');
    }

    public function storeRangkuman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isi_rangkuman' => 'required|string',
            'video_rangkuman' => 'required|file|mimes:mp4,webm',
            'course_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        $video = $request->file('video_rangkuman');
        $video_name = time() . '.' . $video->getClientOriginalExtension();
        $video->move(public_path('storage/video/rangkuman'), $video_name);

        // db insert into
        $rangkuman = DB::table('summary_modules')->insert([
            'summary' => $request->isi_rangkuman,
            'summary_video' => $video_name,
            'course_id' => $request->course_id,
        ]);

        return redirect()->back()->with('success', 'Rangkuman created successfully');
    }

    public function createRangkuman($course_id)
    {
        $course = Course::findorfail($course_id);
        return view('admin.course.module.rangkuman.create', compact('course'));
    }

    public function editRangkuman($id)
    {
        $rangkuman = DB::table('summary_modules')->where('id', $id)->first();
        return view('admin.course.module.rangkuman.edit', compact('rangkuman'));
    }

    public function updateRangkuman(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'isi_rangkuman' => 'required|string',
            'video_rangkuman' => 'nullable|file|mimes:mp4',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }

        $rangkuman = DB::table('summary_modules')->where('id', $id)->first();
        if ($request->hasFile('summary_video')) {
            $old_video = public_path('storage/video/rangkuman/') . $rangkuman->video_rangkuman;
            if (file_exists($old_video)) {
                unlink($old_video);
            }
            $video = $request->file('summary_video');
            $video_name = time() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('storage/video/rangkuman'), $video_name);
        } else {
            $video_name = $rangkuman->summary_video;
        }
        // db update
        $rangkuman = DB::table('summary_modules')->where('id', $id)->update([
            'summary' => $request->isi_rangkuman,
            'summary_video' => $video_name,
        ]);

        return redirect()->route('course.page')->with('status', 'Rangkuman updated successfully');
    }

    public function deleteRangkuman(Request $request, $id)
    {
        $rangkuman = DB::table('module_rangkuman')->where('id', $id)->first();

        // delete video
        $old_video = public_path('storage/video/rangkuman/') . $rangkuman->video_rangkuman;
        if (file_exists($old_video)) {
            unlink($old_video);
        }
        $rangkuman = DB::table('module_rangkuman')->where('id', $id)->delete();
        return redirect()->back()->with('status', 'Rangkuman deleted successfully');
    }
}
