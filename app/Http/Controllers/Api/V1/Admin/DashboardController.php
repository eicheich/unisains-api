<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dahsboard()
    {
        return response()->json([
            'message' => 'Dashboard',
        ], 200);
    }
}