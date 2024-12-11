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
        $statusFilter = $request->input('status');

        // Lọc phòng ban theo tìm kiếm và trạng thái
        $departments = Department::when($search, function($query) use ($search){
            return $query->where('department_name', 'LIKE', "%$search%");
        })
            ->when($statusFilter, function($query) use ($statusFilter){
                return $query->where('status', $statusFilter);
            })
            ->paginate(4);

        // Định nghĩa các trạng thái có sẵn
        $statuses = ['active', 'inactive'];

        return view('admin.dashboard.layout', compact('template', 'departments', 'statuses'));
    }

    // Hiển thị form tạo phòng ban
    public function create()
    {
        $template = 'admin.department.create';

        // Lặp đến khi tìm được mã không trùng lặp
        do {
            $randomNumber = mt_rand(1, 9999);
            $nextId = 'PB' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $exists = Department::where('department_id', $nextId)->exists();
        } while ($exists); // Nếu tồn tại mã này, sinh lại

        // Nếu bạn cần thêm tax_id hoặc lấy danh sách departments:
        // $taxId = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        // $departments = Department::all();

        // Trả về view với $template và $nextId
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
