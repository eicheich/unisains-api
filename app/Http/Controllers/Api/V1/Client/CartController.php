<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
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
        {
            $cart = Cart::where('user_id', $user->id)
                ->where('course_id', $request->course_id)
                ->first();
            if ($cart) {
                return response()->json([
                    'message' => 'Course already in cart',
                ], 200);
            } else {
                try {
                    DB::beginTransaction();
                    $cart = Cart::create([
                        'user_id' => $user->id,
                        'course_id' => $request->course_id,
                    ]);
                    DB::commit();
                    return response()->json([
                        'message' => 'success',
                        'data' => [
                            'cart' => $cart
                        ]
                    ], 200);
                } catch (\Throwable $th) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Something went wrong',
                    ], 500);
                }
            }
        }

    }

    public function all()
    {
        $user = Auth::user();
        $cart = Cart::with('course')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cart == null) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 200);
        } else {
            try {
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'cart' => $cart
                    ]
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
        $cart = DB::table('carts')
            ->where('id', $id)
            ->first();
        if ($cart == null) {
            return response()->json([
                'message' => 'Course not found in cart',
            ], 200);
        }
        try {
            DB::beginTransaction();
            $cart = DB::table('carts')
                ->where('id', $id)->delete();
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

    public function cartCount()
    {
        $user = Auth::user();
        $cartCount = Cart::where('user_id', $user->id)->count();
        if ($cartCount == null) {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'cart-count' => 0
                ]
            ], 200);
        } else {
            try {
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'cart-count' => $cartCount
                    ]
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'something went wrong',
                ], 500);
            }
        }

    }
}
