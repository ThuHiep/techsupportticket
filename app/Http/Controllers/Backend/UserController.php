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
        $template = 'backend.user.index';
        return view('backend.dashboard.layout', compact('template'));
    }

    public function getUserList()
    {
        $users = User::select('user_id', 'username', 'password')->get();
        return response()->json($users);
    }

}
