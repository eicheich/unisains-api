<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_module' => 'required|string|max:255',
            'materi_module' => 'required|string',
            'description' => 'required|string',
            'image_module' => 'required|file|mimes:jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
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

        return redirect()->back()->with('status', 'Module created successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title_module' => 'required|string|max:255',
            'materi_module' => 'required|string',
            'description' => 'required|string',
            'image_module' => 'nullable|file|mimes:jpeg,png|max:2048',
        ]);
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


        // update data
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
}
