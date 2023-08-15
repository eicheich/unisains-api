<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatusHelper{
    public function statusUpdate()
    {
        $transactions = DB::table('transactions')->get();
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->created_at)->addDay();
            if ($date < Carbon::now()) {
                DB::table('transactions')->where('id', $transaction->id)->update([
                    'status' => 'failed'
                ]);
            }
        }

    }

}
