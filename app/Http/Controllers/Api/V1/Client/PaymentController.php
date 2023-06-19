<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

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

        $total = DB::table('transactions')->where('id', $request->transaction_id)->first();

        if (!$total || !isset($total->total_price)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid transaction data',
            ], 400);
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => (int)$total->total_price,
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
}
