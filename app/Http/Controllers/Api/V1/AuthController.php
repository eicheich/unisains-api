<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        if ($validator) {
            DB::table('users')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => 'User created successfully',
            ],201);
        } else {
            return response()->json([
                'message' => $validator,
            ],400);
        }
    }

    public function login(Request $request)
    {
        $validator = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
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
                'status', $th->getMessage()
                
                
            ],500);
        }
    }

}