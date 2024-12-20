<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    // Hiển thị danh sách phòng ban
    public function index(Request $request)
    {
        // Xác thực dữ liệu nhập vào (bỏ 'status' vì không cần tìm kiếm theo trạng thái)
        $request->validate([
            'search' => 'nullable|string|max:255',
            // 'status' => 'nullable|in:active,inactive', // Bỏ nếu không cần
        ]);
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $template = 'admin.department.index';
        $search = trim($request->input('search'));
        // $statusFilter = $request->input('status'); // Bỏ nếu không cần

        // Kiểm tra xem người dùng có thực hiện tìm kiếm hay không và ô tìm kiếm không trống
        $searchPerformed = $request->filled('search');

        if ($searchPerformed) {
            // Xây dựng truy vấn tìm kiếm với 'LIKE %search%' trên cả 'department_name' và 'department_id'
            $query = Department::query();

            if ($search !== '') {
                $query->where(function($q) use ($search) {
                    $q->where('department_name', 'LIKE', "%{$search}%")
                        ->orWhere('department_id', 'LIKE', "%{$search}%");
                });
            }

            // Bỏ bộ lọc trạng thái nếu không cần
            // if ($statusFilter) {
            //     $query->where('status', $statusFilter);
            // }

            // Phân trang với 4 mục mỗi trang
            $departments = $query->paginate(5)->appends($request->all());

            // Lấy tổng số kết quả
            $count = $departments->total();
        } else {
            // Không có tìm kiếm nào được thực hiện hoặc ô tìm kiếm trống, hiển thị tất cả phòng ban
            $departments = Department::paginate(5)->appends($request->all());

            // Lấy tổng số kết quả
            $count = $departments->total();
        }

        // Định nghĩa các trạng thái có sẵn (nếu vẫn cần hiển thị)
        // $statuses = ['active', 'inactive'];

        return view('admin.dashboard.layout', compact('template', 'logged_user','departments', /* 'statuses', */ 'searchPerformed', 'search', 'count'));
    }

    // Hiển thị form tạo phòng ban
    public function create()
    {
        $template = 'admin.department.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
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
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'nextId'));
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
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $department = Department::findOrFail($department_id);
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'department'));
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
