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
        // Lấy toàn bộ khách hàng từ bảng customers
        $customers = customer::all();
        return view('backend.customers.hienthi', compact('customers'));
    }

    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        return view('backend.customers.create');
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
        customer::create($request->all());

        // Thêm logic lưu $data vào database
        return redirect()->route('backend.customers.hienthi')->with('success', 'Khách hàng đã được thêm thành công!');
    }
}
