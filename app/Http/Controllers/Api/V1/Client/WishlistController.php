<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
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
        $wishlist = DB::table('wishlists')->where('user_id', $user->id)->where('course_id', $request->course_id)->first();
        if ($wishlist == null) {
            try {
                $wishlist = Wishlist::create([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                ]);
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'wishlist' => $wishlist
                    ]
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'failed',
                    'errors' => $th->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Course already in wishlist',
            ], 200);
        }
    }

    public function all()
    {
        $user = Auth::user();
        $wishlist = Wishlist::with('course')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        if ($wishlist == null) {
            return response()->json([
                'message' => 'Wishlist is empty',
            ], 200);
        } else {
            try {
                return response()->json([
                    'message' => 'success',
                    'data' => [
                        'wishlist' => $wishlist
                    ]
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'failed',
                    'errors' => $th->getMessage(),
                ], 500);
            }
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        $wishlist = Wishlist::where('id', $id)->first();
        if ($wishlist == null) {
            return response()->json([
                'message' => 'Course not found in wishlist',
            ], 200);
        } else {
            try {
                DB::table('wishlists')->where('id', $id)->delete();
                return response()->json([
                    'message' => 'success',
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'failed',
                    'errors' => $th->getMessage(),
                ], 500);
            }
        }

    }
}
