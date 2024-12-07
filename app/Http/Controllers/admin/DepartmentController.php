<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;


class DepartmentController extends Controller
{
    // Hiển thị danh sách phòng ban
    public function index(Request $request)
    {
        $template = 'admin.department.index';
        // Kiểm tra xem có yêu cầu tìm kiếm không
        $search = $request->input('search');

        // Nếu có tìm kiếm, lọc dữ liệu theo mã hoặc tên phòng ban
        $departments = Department::when($search, function ($query) use ($search) {
            return $query->where('department_id', 'LIKE', "%$search%")
                ->orWhere('department_name', 'LIKE', "%$search%");
        })
            // Phân trang với 10 bản ghi mỗi trang
            ->paginate(10);

        // Trả về view với dữ liệu phòng ban
        return view('admin.dashboard.layout', compact('template', 'departments'));
    }

    // Hiển thị form tạo phòng ban mới
    public function create()
    {
        $template = 'admin.department.create';
        // Sinh department_id ngẫu nhiên (ví dụ: PB0001)
        $randomId = 'PB' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return view('admin.dashboard.layout', compact('template', 'randomId'));
    }

    // Lưu phòng ban mới
    public function store(Request $request)
    {
        // Validate dữ liệu nhập vào
        $request->validate([
            'department_id' => 'required|unique:department,department_id',
            'department_name' => 'required|unique:department,department_name|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Tạo phòng ban mới
        Department::create([
            'department_id' => $request->input('department_id'),
            'department_name' => $request->input('department_name'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('admin.department.index')
            ->with('success', 'Phòng ban đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa phòng ban
    public function edit($department_id)
    {
        $template = 'backend.department.edit';
        $department = Department::findOrFail($department_id);
        return view('admin.dashboard.layout', compact('template', 'department'));
    }

    // Cập nhật phòng ban
    public function update(Request $request, $department_id)
    {
        $department = Department::findOrFail($department_id);

        // Validate dữ liệu nhập vào
        $request->validate([
            'department_name' => 'required|unique:department,department_name,' . $department->department_id . ',department_id|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Cập nhật phòng ban
        $department->update([
            'department_name' => $request->input('department_name'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('admin.department.index')
            ->with('success', 'Thông tin phòng ban đã được cập nhật!');
    }

    // Xóa phòng ban
    public function destroy($department_id)
    {
        $department = Department::findOrFail($department_id);
        $department->delete();

        return redirect()->route('admin.department.index')
            ->with('success', 'Phòng ban đã được xóa!');
    }
}
