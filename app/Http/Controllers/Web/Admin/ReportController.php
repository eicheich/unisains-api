<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function all(){
        $reports = DB::table('reports')->get();

        return view('admin.report.all', compact('reports'));
        
    }
}
