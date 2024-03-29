<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            return redirect()->route('dashboard.page');
        } else {
            return redirect()->back()->withErrors(['password' => 'Password is incorrect']);
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login.page');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        try {
            DB::beginTransaction();
            $user = DB::table('users')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role, // 'admin', 'teacher', 'student
                'password' => bcrypt($request->password),
            ]);
            DB::commit();
            return redirect()->back()->with('success', ' created successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

//    show
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
//        activity_log dari user ini
        $activity_logs = DB::table('activity_log')->where('causer_id', $id)->paginate(10);
        return view('admin.user.show', compact('user','activity_logs'));
    }

}
