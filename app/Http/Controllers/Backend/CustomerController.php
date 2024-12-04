<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer; // Import Model Customer
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Hiển thị danh sách khách hàng
    public function index()
    {
        $template = 'backend.customer.index';
        $customers = Customer::with('user')->get(); // Load quan hệ user để lấy email
        return view('backend.dashboard.layout', compact('template', 'customers'));
    }

    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        $template = 'backend.customer.create';
        return view('backend.dashboard.layout', compact('template'));
    }

    // Hiển thị form chỉnh sửa khách hàng
    public function edit($customer_id)
    {
        $template = 'backend.customer.edit';
        $customers = Customer::findOrFail($customer_id);
        return view('backend.dashboard.layout', compact('template', 'customers'));
    }
    public function update(Request $request, $customer_id)
    {
        $customers = Customer::findOrFail($customer_id);
        $customers->name = $request->input('name');
        $customers->email = $request->input('email');
        $customers->save();

        return redirect()->route('backend.customer.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    // Xóa khách hàng
    public function destroy($customer_id)
    {
        $customers = Customer::findOrFail($customer_id);
        $customers->delete();

        return redirect()->route('backend.customer.index')
            ->with('success', 'Khách hàng đã được xóa!');
    }


    // Lưu khách hàng mới
    public function store(Request $request)
    {
        // Sinh customer_id ngẫu nhiên
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        // Kiểm tra trùng lặp trong database
        while (Customer::where('customer_id', $randomId)->exists()) {
            $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }

        // Tạo khách hàng mới
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->name = $request->input('full_name');
        $customer->email = $request->input('email');
        $customer->save();

        return redirect()->route('backend.customer.index')
            ->with('success', 'Khách hàng đã được thêm thành công!');
    }
}
