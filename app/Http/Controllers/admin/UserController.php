<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){

    }
    public function index()
    {
        $template = 'admin.user.index';
        return view('admin.dashboard.layout', compact('template'));
    }

    public function getUserList()
    {
        $users = User::select('user_id', 'username', 'password')->get();
        return response()->json($users);
    }

}
