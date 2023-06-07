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
        return view('admin.dashboard', compact('course', 'user', 'transaction', 'report'));
    }
}
