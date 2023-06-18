<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function all(){
//        report with paginate
        $reports = Report::with('user')->paginate(10);

        return view('admin.report.all', compact('reports'));

    }
}
