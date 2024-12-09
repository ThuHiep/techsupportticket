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
        $search = $request->input('search');

        $departments = Department::when($search, function ($query) use ($search) {
            return $query->where('department_id', 'LIKE', "%$search%")
                ->orWhere('department_name', 'LIKE', "%$search%");
        })->paginate(10);

        return view('admin.dashboard.layout', compact('template', 'departments'));
    }

    // Hiển thị form tạo phòng ban
    public function create()
    {
        $template = 'admin.department.create';

        // Đếm số lượng bản ghi
        $count = Department::count();
        $nextNumber = $count + 1; // Số thứ tự mới

        // Định dạng mã phòng ban, ví dụ PB + 4 chữ số
        // Nếu cần 4 chữ số, ví dụ PB0001, PB0002...
        $nextId = 'PB' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.dashboard.layout', compact('template', 'nextId'));
    }

    // Lưu phòng ban mới
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|unique:department,department_id',
            'department_name' => 'required|unique:department,department_name|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create([
            'department_id' => $request->input('department_id'),
            'department_name' => $request->input('department_name'),
            'status' => $request->input('status'),
        ]);

        // Flash message vào session
        return redirect()->route('department.index')->with('success', 'Phòng ban đã được thêm thành công!');
    }


    // Chỉnh sửa phòng ban
    public function edit($department_id)
    {
        $template = 'admin.department.edit';
        $department = Department::findOrFail($department_id);
        return view('admin.dashboard.layout', compact('template', 'department'));
    }

    // Cập nhật phòng ban
    public function update(Request $request, $department_id)
    {
        $department = Department::findOrFail($department_id);

        $request->validate([
            'department_name' => 'required|unique:department,department_name,' . $department->department_id . ',department_id|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update([
            'department_name' => $request->input('department_name'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('department.index')
            ->with('success', 'Thông tin phòng ban đã được cập nhật!');
    }

    // Xóa phòng ban
    public function destroy($department_id)
    {
        $department = Department::findOrFail($department_id);
        $department->delete();

        return redirect()->route('department.index')
            ->with('success', 'Phòng ban đã được xóa!');
    }
}
