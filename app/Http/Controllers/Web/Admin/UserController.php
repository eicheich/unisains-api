<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function all()
    {
        $users = DB::table('users')->where('role', 'user')->get();
        $admin = DB::table('users')->where('role', 'admin')->get();
        return view('admin.user.all', compact('users', 'admin'));

    }
}
