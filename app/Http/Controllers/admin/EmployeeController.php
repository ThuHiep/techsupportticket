<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCreatedMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.employee.index';
        $search = $request->input('search');

        $employees = Employee::join('user', 'user.user_id', '=', 'employee.user_id')
            ->join('role', 'role.role_id', '=', 'user.role_id')
            ->where('role.role_id', '=', 2)
            ->where('status', 'active')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('employee_id', 'LIKE', "%$search%")
                        ->orWhere('full_name', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('email', 'LIKE', "%$search%");
                        });
                });
            })
            ->select('employee.*', 'user.*', 'role.role_name')
            ->orderBy('user.user_id')
            ->paginate(3);

        return view('admin.dashboard.layout', compact('template', 'employees', 'search'));
    }
    public function createEmployee()
    {
        $template = 'admin.employee.create';
        return view('admin.dashboard.layout', compact('template'));
    }

    public function saveEmployee(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employee,email',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Nam,Nữ',
            'phone' => 'required|numeric|digits_between:10,20',
            'address' => 'required|string|max:225',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Sinh employee_id và user_id
        $randomId = 'NV' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        while (Employee::where('employee_id', $randomId)->exists()) {
            $randomId = 'NV' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        }

        $randomUserId = 'TK' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        while (User::where('user_id', $randomUserId)->exists()) {
            $randomUserId = 'TK' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        }

        // Upload profile image
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            if ($image->isValid()) {
                $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                $profileImagePath = $imageName;
                $image->move(public_path('admin/img/employee'), $imageName);
            }
        }
        $password = Str::random(11);
        // Tạo user mới
        $user = new User();
        $user->user_id = $randomUserId;
        $user->username = $request->input('username');
        $user->password = Hash::make($password);
        $user->role_id = "2";
        $user->status = "active";
        $user->create_at = now();
        $user->update_at = now();
        $user->save();

        // Tạo nhân viên mới
        $employee = new Employee();
        $employee->employee_id = $randomId;
        $employee->user_id = $randomUserId;
        $employee->full_name = $request->input('full_name');
        $employee->email = $request->input('email');
        $employee->date_of_birth = $request->input('date_of_birth');
        $employee->gender = $request->input('gender');
        $employee->phone = $request->input('phone');
        $employee->address = $request->input('address');
        $employee->profile_image = $profileImagePath;
        $employee->create_at = now();
        $employee->update_at = now();
        $employee->save();

        // Gửi email thông báo
        Mail::to($employee->email)->send(new EmployeeCreatedMail($employee, $user->user_id, $user->username, $password));

        return redirect()->route('employee.index')
            ->with('success', 'Nhân viên đã được thêm thành công và email đã được gửi!');
    }


    public function editEmployee($employee_id)
    {
        $template = 'admin.employee.edit';
        $employee = Employee::with(['user.role'])
            ->where('employee_id', '=', $employee_id)
            ->first();
        return view('admin.dashboard.layout', compact('template', 'employee'));
    }

    // Cập nhật thông tin nhân viên
    public function updateEmployee(Request $request, $employee_id)
    {
        // Lấy thông tin khách hàng cũ
        $employee = Employee::findOrFail($employee_id);
        $user = User::findOrFail($employee->user_id);

        // Kiểm tra nếu có hình ảnh được upload
        $profileImagePath = $employee->profile_image;
        if ($request->hasFile('profile_image')) {
            // Xóa ảnh cũ nếu có
            if ($profileImagePath && file_exists(public_path('admin/img/employee/' . $profileImagePath))) {
                unlink(public_path('admin/img/employee/' . $profileImagePath));
            }

            // Lưu ảnh mới
            $image = $request->file('profile_image');
            if ($image->isValid()) {
                $imageName = 'update_' . time() . '.' . $image->getClientOriginalExtension();
                $profileImagePath = $imageName;  // Cập nhật đường dẫn ảnh mới
                $image->move(public_path('admin/img/employee/'), $imageName);  // Di chuyển ảnh mới vào thư mục
            }
        }

        // Cập nhật thông tin nhân viên
        $employee->full_name = $request->input('full_name');
        $employee->date_of_birth = $request->input('date_of_birth');
        $employee->gender = $request->input('gender');
        $employee->phone = $request->input('phone');
        $employee->address = $request->input('address');
        $employee->profile_image = $profileImagePath;
        $employee->email = $request->input('email');
        $employee->update_at = now();

        $user->username = $request->input('username');
        $user->role_id = $request->input('role_name');
        $user->status = $request->input('status');
        $user->update_at = now();
        // Lưu thông tin nhân viên vào database
        $employee->save();
        $user->save();

        return redirect()->route('employee.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    public function deleteEmployee($id)
    {
        // Xóa Employee và User liên quan
        Employee::where('user_id', $id)->delete();
        User::where('user_id', $id)->delete();

        session()->flash('message', 'The employee was successfully deleted!');

        return redirect()->route('employee.index')->with('success', 'Tài khoản đã được xóa.');
    }
}
