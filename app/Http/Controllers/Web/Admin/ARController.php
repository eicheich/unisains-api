<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ARController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_ar' => 'required|mimes:jpeg,png',
            'course_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }
        $image = $request->file('image_ar');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('storage/images/ar'), $image_name);
        $ar = DB::table('augmented_realities')->insert([
            'image_ar' => $image_name,
            'course_id' => $request->course_id,
        ]);

        return redirect()->back()->with('status', 'AR created successfully');
    }

    public function edit($id)
    {
        $ar = DB::table('augmented_realities')->where('id', $id)->first();
        return view('admin.course.ar.edit', compact('ar'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image_ar' => 'nullable|mimes:jpeg,png',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }
        $ar = DB::table('augmented_realities')->where('id', $id)->first();
        if ($request->hasFile('image_ar')) {
            $old_image = public_path('storage/images/ar/') . $ar->image_ar;
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $image = $request->file('image_ar');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('storage/images/ar'), $image_name);
            DB::table('augmented_realities')->where('id', $id)->update([
                'image_ar' => $image_name,
            ]);
        } else {
            DB::table('augmented_realities')->where('id', $id)->update([
                'image_ar' => $ar->image_ar,
            ]);
        }
        return redirect()->route('course.show', $ar->course_id)->with('status', 'AR updated successfully');
    }

    public function delete($id)
    {
        $ar = DB::table('augmented_realities')->where('id', $id)->first();
        $old_image = public_path('storage/images/ar/') . $ar->image_ar;
        if (file_exists($old_image)) {
            unlink($old_image);
        }
        DB::table('augmented_realities')->where('id', $id)->delete();
        return redirect()->back()->with('status', 'AR deleted successfully');
    }
}
