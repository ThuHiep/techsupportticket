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
        $users = Customer::select('customer_id', 'full_name', 'phone', 'status')
            ->whereNull('status') // Chỉ lấy các tài khoản chưa được phê duyệt
            ->get();

        return response()->json($users);
    }

    public function approveCustomer(Request $request, $customerId)
    {
        $customer = Customer::find($customerId);

        if ($customer) {
            $customer->status = 'active'; // Cập nhật trạng thái thành "active"
            $customer->save();

            return response()->json(['status' => 'success', 'message' => 'Khách hàng đã được phê duyệt thành công!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy khách hàng này.']);
    }



}
