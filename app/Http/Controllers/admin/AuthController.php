<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){

    }
    public function login(){
        return view('admin.auth.login');
    }

    // Quên mật khẩu
    public function showForgotPass(){
        return view('admin.auth.quenmatkhau');
    }
}
