<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Mail\SuccessPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $trx = DB::table('transactions')->where('id', $request->transaction_id)->first();
        $user = Auth::user();

        if (!$trx || !isset($trx->total_price)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid transaction data',
            ], 400);
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => $trx->id,
                'gross_amount' => (int)$trx->total_price,
            ),
            'customer_details' => array(
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => 626229933,
            ),
        );

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'snap_token' => $snapToken,
        ]);
    }
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if (!$hashed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 400);
        }

        $transaction = DB::table('transactions')->where('id', $request->order_id)->first();
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            try {
                DB::beginTransaction();
                DB::table('transactions')->where('id', $request->order_id)->update([
                    'status' => 'success',
                    'updated_at' => now(),
                ]);
                $mycourse = DB::table('my_courses')->insert([
                    'course_id' => $transaction->course_id,
                    'user_id' => $transaction->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user = DB::table('users')->where('id', $transaction->user_id)->first();
                Mail::to($user->email)->send(new SuccessPayment([
                    'name' => $user->first_name . ' ' . $user->last_name,
//                    'course' => $transaction->course_name,
                    'date' => Carbon::now()->format('d F Y'),
                    'id' => $transaction->id,
                    'email' => $user->email,
                    'paymentMethod' => $request->payment_type,
                    'total' => $transaction->total_price,
//                    'image' => $transaction->course_thumbnail
                ]));
                DB::commit();
                $modelUser = User::find($user->id);
                activity()->causedBy($modelUser)->log('Success Payment '. $user->email);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'transaction success',
            ], 200);
        } elseif ($request->transaction_status == 'expire') {
            $transaction->update(['status' => 'failed']);
            return response()->json([
                'status' => 'error',
                'message' => 'transaction expired',
            ], 200);
        } elseif ($request->transaction_status == 'cancel') {
            $transaction->update(['status' => 'failed']);
            return response()->json([
                'status' => 'error',
                'message' => 'transaction canceled',
            ], 200);
        } elseif ($request->transaction_status == 'deny') {
            $transaction->update(['status' => 'failed']);
            return response()->json([
                'status' => 'error',
                'message' => 'transaction denied',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'something went wrong',
        ], 200);

    }

    public function test()
    {
        $user = Auth::user();
        $data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'course' => 'Jantung Abraham Lincoln',
            'date' => now(),
            'id' => 5263919,
            'email' => $user->email,
            'paymentMethod' => 'BCA',
            'total' => 100000,
            'image' => 'https://xkeuwn.stripocdn.email/content/guids/CABINET_2e2246de1276cf1b786e85afa37898e29cd6b2e37247bb8ec807d3db6572304a/images/1690501807.png'
        ];
        try {
            Mail::to($user->email)->send(new SuccessPayment($data));

            return response()->json([
                'status' => 'success',
                'message' => 'email sent',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

    }
}
