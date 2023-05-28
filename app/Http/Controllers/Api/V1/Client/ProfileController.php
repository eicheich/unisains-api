<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $my_course = DB::table('my_course')
            ->join('courses', 'courseS.id', '=', 'my_course.course_id')
            ->where('my_course.user_id', $user->id)
            ->first();
        if ($my_course == null) {
            return response()->json([
                'user' => $user,
                'message' => 'You have not purchased any course',
            ], 200);
        } else
            return response()->json([
                'user' => $user,
                'my_course' => $my_course,
            ], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            DB::beginTransaction();
            $user = DB::table('users')->where('id', $user->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Profile updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
