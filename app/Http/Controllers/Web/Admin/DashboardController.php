<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $course = Course::all()->count();
        $user = User::where('role', 'user')->count();
        $transaction = Transaction::all()->count();
        $report = Report::all()->count();

        $userChart = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
        $transactionChart = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
        return view('admin.dashboard', compact('course', 'user', 'transaction', 'report', 'userChart', 'transactionChart'));

    }
}
