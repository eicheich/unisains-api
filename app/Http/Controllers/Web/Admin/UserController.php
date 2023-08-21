<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function all()
    {
        $users = DB::table('users')->where('role', 'user')->paginate(10);
        $admin = DB::table('users')->where('role', 'admin')->paginate(10);
        $teacher = DB::table('users')->where('role', 'teacher')->paginate(10);
        return view('admin.user.all', compact('users', 'admin','teacher'));
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

    public function search(Request $request)
    {
//        cari dari username atau email
        $search = $request->search;
        $users = User::where(function($query) use ($search) {
            $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(9);
        $admin = User::where('role', 'admin')
            ->where(function($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->paginate(9);
        $teacher = User::where('role', 'teacher')
            ->where(function($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->paginate(9);

        return view('admin.user.all', compact('users','admin','teacher'));


    }
}
