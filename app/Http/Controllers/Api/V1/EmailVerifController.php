<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerifRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmailVerifController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    public function verifEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
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
                'message' => 'Email verification failed',
                'error' => $otp2,
            ], 400);
        }
        $user = User ::where('email', $user->email)->first();
        if ($user){
            try {
                DB::beginTransaction();
                $user->email_verified_at = now();
                $user->save();
                DB::commit();
                return response()->json([
                    'message' => 'Email verification success',
                    'user' => $user,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Email verification failed',
                    'error' => $e->getMessage(),
                ], 400);
            }
        }

    }
    //
}
