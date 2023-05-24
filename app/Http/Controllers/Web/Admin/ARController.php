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
}
