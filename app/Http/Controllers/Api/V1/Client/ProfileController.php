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
            ->join('courses', 'courses.id', '=', 'my_course.course_id')
            ->where('my_course.user_id', $user->id)
            ->first();

        if ($my_course == null) {
            $response = [
                'message' => "success",
                'data' => [
                    'user' => $user,
                    'my_course' => 'You have not purchased any course',
                ],
            ];
        } else {
            $response = [
                'message' => "success",
                'data' => [
                    'user' => $user,
                    'my_course' => $my_course,
                ],
            ];
        }

        return response()->json($response, 200);
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        if ($request->hasFile('avatar')) {
            if ($user->avatar != null) {
                $old_avatar = public_path('storage/images/avatar') . '/' . $user->avatar;
                if (file_exists($old_avatar)) {
                    unlink($old_avatar);
                }
            }
            $avatar = $request->file('avatar');
            $avatar_name = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('storage/images/avatar'), $avatar_name);
        } else {
            $avatar_name = $user->avatar;
        }
        try {
            DB::beginTransaction();
            $user = DB::table('users')->where('id', $user->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'avatar' => $avatar_name,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $th->getMessage(),
            ], 500);
        }

    }
}
