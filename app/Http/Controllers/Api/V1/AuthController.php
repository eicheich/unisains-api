<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator) {
            try {
                DB::beginTransaction();
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                ]);
                $token = $user->createToken('token-verify')->plainTextToken;
                $user->notify(new EmailVerificationNotification());
                DB::commit();
                return response()->json([
                    'message' => 'Register success',
                    'user' => $user,
                    'token-verify' => $token,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Register failed',
                    'error' => $e->getMessage(),
                ], 400);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('token-name')->plainTextToken;
                    return response()->json([
                        'message' => 'Login success',
                        'user' => $user,
                        'token' => $token,
                    ],200);
                } else {
                    return response()->json([
                        'message' => 'Password is incorrect',
                    ],400);
                }
            } else {
                return response()->json([
                    'message' => 'User not found',
                ],400);
            }
        } else {
            return response()->json([
                'message' => $validator,
            ],400);
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

    public function v1resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Email verification failed',
                'error' => $validator->errors(),
            ], 400);
        }

    }
}
