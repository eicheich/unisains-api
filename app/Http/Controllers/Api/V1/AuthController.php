<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Validator;
use Ichtrojan\Otp\Otp;

class AuthController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }
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
                activity()->causedBy($user)->log('Created Account '. $user->email);
                $token = $user->createToken('token-verify')->plainTextToken;
                $user->notify(new EmailVerificationNotification());
                DB::commit();
                activity()->causedBy($user)->log('Requested OTP for Verify Email '. $user->email);
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
                    activity()->causedBy($user)->log('Logged In '. $user->email);
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
        $user = $request->user();
        $token = $user->currentAccessToken()->delete();
        if ($token) {
            activity()
                ->causedBy($user)
                ->log('Logged Out ' . $user->email);
            return response()->json([
                'message' => 'Logout success',
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
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
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('token-forgotpw')->plainTextToken;
        $user->notify(new ResetPasswordNotification());
        activity()->causedBy($user)->log('Requested OTP Reset Password '. $user->email);
        return response()->json([
            'message' => 'success',
            'token' => $token,
        ],200);
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Email verification failed',
                'error' => $validator->errors(),
            ], 400);
        }
        $user = Auth::user();
        $otp2 = $this->otp->validate($user->email, $request->otp);
        if (!$otp2->status) {
            return response()->json([
                'message' => 'error',
                'error' => $otp2,
            ],401);
        }
        try {
            DB::beginTransaction();
            $user->password = Hash::make($request->password);
            $user->save();
            DB::commit();
            activity()->causedBy($user)->log('Reset Password '. $user->email);
            return response()->json([
                'message' => 'success',
            ],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ],500);
        }



    }
}
