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
        }
        $cart = DB::table('carts')->insert([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
        ]);
        if ($cart) {
            return response()->json([
                'message' => 'Course added to cart',
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 500);
    }
}
