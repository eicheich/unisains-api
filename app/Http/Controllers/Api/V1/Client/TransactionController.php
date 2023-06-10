<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $carbonckeck = Carbon::now();
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
                    'total_price' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::commit();
                return response()->json([
                    'message' => 'Transaction added',
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status', $th->getMessage()
                ], 500);
            }
        } elseif ($course->is_paid == 1 && $course->discount == null) {
            try {
                DB::beginTransaction();
                $transaction = DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'status' => 'pending',
                    'code_transaction' => 'TRU' . time(),
                    'total_price' => $course->price,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::commit();
                return response()->json([
                    'message' => 'Transaction added',
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status', $th->getMessage()
                ], 500);
            }
        } else {
            $price_after_discount = $course->price - ($course->price * $course->discount / 100);
            try {
                DB::beginTransaction();
                $transaction = DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'status' => 'pending',
                    'code_transaction' => 'TRU' . time(),
                    'total_price' => $price_after_discount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::commit();
                return response()->json([
                    'message' => 'Transaction added',
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status', $th->getMessage()
                ], 500);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status', $th->getMessage()
                ], 500);
            }
        }
    }

    public function schedule($transactionId)
    {
        $transaction = DB::table('transactions')->where('id', $transactionId)->first();

        if ($transaction->status == 'pending') {
            // Calculate due time as 1 day after the transaction's creation time
            $dueTime = Carbon::parse($transaction->created_at)->addDay();
            // Schedule the task to run at the due time
            Cache::put('transaction_due_' . $transaction->id, true, $dueTime);
            // Register the task
            Cache::put('transaction_due_task_' . $transaction->id, function () use ($transactionId) {
                $this->updateTransactionStatus($transactionId);
            }, $dueTime);
        }
    }
    public function all()
    {
        $user = Auth::user();

        // check if transaction exists
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

        $currentTime = Carbon::now();

        // Calculate the remaining time for each pending transaction
        foreach ($transactions as $transaction) {
            if ($transaction->status == 'pending') {
                $createdAt = Carbon::parse($transaction->created_at);
                $dueTime = $createdAt->addDay();

                if ($dueTime > $currentTime) {
                    $remainingTime = $dueTime->diffForHumans($currentTime, [
                        'syntax' => CarbonInterface::DIFF_ABSOLUTE,
                        'parts' => 2,
                    ]);
                } else {
                    $remainingTime = 'Expired';
                    DB::table('transactions')->where('id', $transaction->id)->update([
                        'status' => 'failed',
                        'updated_at' => Carbon::now(),
                    ]);
                }

                $transaction->remaining_time = $remainingTime;
            }
        }

        return response()->json([
            'message' => 'Success',
            'data' => $transactions,
        ], 200);
    }
}
