<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function all()
    {
        $users = DB::table('users')->where('role', 'user')->paginate(10);
        $admin = DB::table('users')->where('role', 'admin')->paginate(10);
        return view('admin.user.all', compact('users', 'admin'));
    }

    public function delete($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        try {
            DB::beginTransaction();
            DB::table('users')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
