<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        } else
            try {
                DB::beginTransaction();
                $cart = DB::table('carts')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                ]);
                DB::commit();
                return response()->json([
                    'message' => 'Course added to cart',
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'Something went wrong',
                ], 500);
            }
    }

    public function all()
    {
        $user = Auth::user();
        $cart = DB::table('carts')
            ->join('courses', 'courses.id', '=', 'carts.course_id')
            ->where('carts.user_id', $user->id)
            ->first();
        if ($cart->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 200);
        } else {
            try {
                DB::beginTransaction();
                $cart = DB::table('carts')
                    ->join('courses', 'courses.id', '=', 'carts.course_id')
                    ->where('carts.user_id', $user->id)
                    ->get();
                DB::commit();
                return response()->json([
                    'message' => 'Cart is empty',
                    'data' => $cart,
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'Something went wrong',
                ], 500);
            }
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $cart = DB::table('carts')
                ->where('user_id', $user->id)
                ->where('course_id', $id)->first();
            if ($cart->isEmpty()) {
                return response()->json([
                    'message' => 'Cart is empty',
                ], 200);
            }

            DB::commit();
            return response()->json([
                'message' => 'Course deleted from cart',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
