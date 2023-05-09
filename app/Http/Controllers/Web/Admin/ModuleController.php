<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_module' => 'required|string|max:255',
            'materi_module' => 'required|string',
            'description' => 'required|string',
            'image_module' => 'required|file|mimes:jpeg,png|max:2048',
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
    }
}
