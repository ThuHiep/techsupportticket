<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct() {}
    public function login()
    {
        return view('admin.auth.login');
    }

    // Quên mật khẩu
    public function showForgotPass()
    {
        return view('admin.auth.quenmatkhau');
    }

    // Thay đổi mật khẩu
    public function changePass($user_id)
    {
        return view('admin.auth.thaydoimatkhau', compact('user_id'));
    }
    public function updatePass(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (!Hash::check($request->input('old-password'), $user->password)) {
            return back()->withErrors(['old-password' => 'Mật khẩu cũ không đúng!']);
        }

        if ($request->input('new-password') !== $request->input('confirm-password')) {
            return back()->withErrors(['confirm-password' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->update_at = now();
        $user->save();

        return redirect()->route('login.login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
