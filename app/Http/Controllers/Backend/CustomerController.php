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
        $template = 'backend.customer.hienthi';
        $customers = Customer::all();
        return view('backend.dashboard.layout', compact('template', 'customers'));
    }

    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        return view('backend.customer.create');
    }

    // Hiển thị form chỉnh sửa khách hàng
    public function edit($customer_id)
    {
        $customers = Customer::findOrFail($customer_id);
        return view('backend.customer.edit', compact('customers'));
    }
    // Xóa khách hàng
    public function destroy($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $customer->delete();
        return redirect()->route('backend.customer.hienthi')->with('success', 'Khách hàng đã được xóa!');
    }

    // Lưu khách hàng mới
    public function store(Request $request)
    {
        // Xử lý lưu thông tin vào database
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);
        // Lưu dữ liệu vào database
        Customer::create($request->all());

        // Thêm logic lưu $data vào database
        return redirect()->route('backend.customer.hienthi')->with('success', 'Khách hàng đã được thêm thành công!');
    }
}
