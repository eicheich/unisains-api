<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    public function rate(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        } else
            try {
                $rate = DB::table('rates')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'rate' => $request->rate,
                    'comment' => $request->comment,
                    'created_at' => now()
                ]);
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'rate' => $rate
                    ]
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => $th
                ], 500);
            }
    }

    public function editRate($id)
    {
        $user = Auth::user();
        $rate = DB::table('rates')->where('id', $id)->first();
        if ($rate){
            if ($rate->user_id == $user->id) {
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'rate' => $rate
                    ]
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to edit this rate',
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Rate not found',
            ], 404);
        }
    }

    public function updateRate($id, Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required'
        ]);
        $rate = DB::table('rates')->where('id', $id)->first();
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
            if ($rate->user_id == $user->id) {
                try {
                    $rate = DB::table('rates')->where('id', $id)->update([
                        'rate' => $request->rate,
                        'comment' => $request->comment,
                        'updated_at' => now()
                    ]);
                    return response()->json([
                        'message' => 'success',
                        'data' => [
                            'rate' => $rate
                        ]
                    ], 200);
                } catch (\Throwable $th) {
                    DB::rollback();
                    return response()->json([
                        'message' => $th
                    ], 500);
                }
            }

    }
}
