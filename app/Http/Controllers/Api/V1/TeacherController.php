<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CertificateGenerator;

class TeacherController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $credentials = request(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Email or Password is wrong',
                ], 401);
            }
            $user = auth()->user();
            if ($user->role != 'teacher') {
                return response()->json([
                    'message' => 'Unauthorized, or you are not a teacher'
                ], 401);
            }
            $token = $user->createToken('authToken')->plainTextToken;
            $response = [
                'message' => 'success',
                'user' => $user,
                'token' => $token,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        if ($token) {
            return response()->json([
                'message' => 'Logout success',
            ],200);
        } else {
            return response()->json([
                'status' => 'failed',
            ],500);
        }
    }

    public function dashboard()
    {
    }

    public function generate()
    {
        try {
            CertificateGenerator::generate('Lionel Messi K', 'Jantung Angry Bird', '2023-08-03', 1,3);
            return response()->json([
                'message' => 'success',
//                link to certificate

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $course = Course::with('category')->find($id);
        if ($course == null) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'course' => $course
                ]
            ], 200);
        }
    }

    public function update($id, Request $request)
    {
        $course = Course::find($id);
        if ($course == null) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'title_course' => 'required',
                'description' => 'required',
                'thumbnail' => 'nullable|image',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } else {
                if ($request->hasFile('thumbnail')) {
                    $old_thumbnail = public_path('storage/images/thumbnail/') . $course->thumbnail;
                    if (file_exists($old_thumbnail)) {
                        unlink($old_thumbnail);
                    }
                    $thumbnail = $request->file('thumbnail');
                    $thumbnail_name = time() . '.' . $thumbnail->getClientOriginalExtension();
                    $thumbnail->move(public_path('storage/images/thumbnail/'), $thumbnail_name);
                } else {
                    $thumbnail_name = $course->thumbnail;
                }
                try {
                    $course->update([
                        'title_course' => $request->title_course,
                        'description' => $request->description,
                        'thumbnail' => $thumbnail_name,
                    ]);
                    return response()->json([
                        'message' => 'success',
                        'data' => [
                            'course' => $course
                        ]
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'failed',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }
        }

    }
}
