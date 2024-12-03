<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){

    }
    public function index()
    {
        // Lấy toàn bộ dữ liệu từ bảng user
        $users = User::all();
        return view('backend.users.index', compact('users'));
    }
}
