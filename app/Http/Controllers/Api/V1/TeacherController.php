<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
            CertificateGenerator::generate('Lionel Messi K', 'Jantung Angry Bird', '2023-08-03');
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
}
