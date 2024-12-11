<?php

namespace App\Http\Controllers\guest;
use App\Http\Controllers\Controller;
use App\Models\Customer;
//use App\Models\Request;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){

    }
    public function index()
    {
        $template = 'guest.user.index';
        return view('guest.dashboard.layout', compact('template'));
    }
    public function getUserList()
    {
        $users = Customer::select('customer_id', 'full_name', 'status')
            ->whereNull('status') // Chỉ lấy các tài khoản chưa được phê duyệt
            ->get();

        return response()->json($users);
    }
    public function indexAccount()
    {
        return view('guest.account.index');
    }

}
