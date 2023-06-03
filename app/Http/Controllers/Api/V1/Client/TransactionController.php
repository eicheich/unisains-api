<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $course = DB::table('courses')->where('id', $request->course_id)->first();
        if ($course == null) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        }
        if ($course->is_paid == 0) {
            try {
                DB::beginTransaction();
                $transaction = DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'status' => 'pending',
                    'code_transaction' => 'TRU' . time(),
                    'total_price' => null
                ]);
                DB::commit();
                $this->schedule($transaction);
                return response()->json([
                    'message' => 'Transaction added',
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Something went wrong',
                ], 500);
            }
            # code...
        }

    }
    public function all()
    {
        $user = Auth::user();
        // check if transaction is exist
        $transaction = DB::table('transactions')->where('user_id', $user->id)->get();
        if ($transaction->count() == 0) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }
        $transactions = DB::table('transactions')
            ->join('courses', 'transactions.course_id', '=', 'courses.id')
            ->select('transactions.*', 'courses.title_course', 'courses.image_course', 'courses.price', 'courses.is_paid', 'courses.description')
            ->where('transactions.user_id', $user->id)
            ->get();
        return response()->json([
            'message' => 'Success',
            'data' => $transactions,
        ], 200);
            
    }

    public function schedule(schedule $schedule, $transaction)
    {
        $schedule->call(function () use ($transaction) {
            $transaction = DB::table('transactions')->where('id', $transaction->id)->first();
            if ($transaction->status == 'pending') {
                if ($transaction->created_at < now()->subMinutes(1)) {
                    DB::table('transactions')->where('id', $transaction->id)->update([
                        'status' => 'failed',
                    ]);
                }
                
            }
        })->everyMinute();
    }
    // {
    //     $user = Auth::user();
    //     // delete transaction in 24 hours
    //     $transaction = DB::table('transactions')->where('user_id', $user->id)->where('created_at', '<', now()->subHours(24))->delete();
    // }
}
