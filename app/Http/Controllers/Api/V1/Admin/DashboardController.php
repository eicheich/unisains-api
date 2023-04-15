<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUser = DB::table('users')->count();
        $totalTransaction = DB::table('transactions')->count();
        $totalCourse = DB::table('courses')->count();
        $rate = DB::table('rates')->latest()->first();


        return response()->json([
            'user' => $totalUser,
            'transaction' => $totalTransaction,
            'course' => $totalCourse,
            'rate' => $rate
        ]);
    }
}
