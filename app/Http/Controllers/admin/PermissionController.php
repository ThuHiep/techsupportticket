<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCreatedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.permission.index';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $search = $request->input('search');

        $query = Employee::join('user', 'user.user_id', '=', 'employee.user_id')
            ->join('role', 'role.role_id', '=', 'user.role_id')
            ->where('status', 'active');

        $query = Employee::join('user', 'user.user_id', '=', 'employee.user_id')
            ->join('role', 'role.role_id', '=', 'user.role_id')
            ->where('status', 'active');

        if ($search) {
            if (preg_match('/^AD\d{8}$/', $search) || preg_match('/^NV\d{8}$/', $search)) {
                // Tìm theo mã admin hoặc nhân viên
                $query->where('employee_id', '=', $search);
            } else {
                // Tìm theo tên
                $query->where('full_name', 'LIKE', "%$search%");
            }
        }
        $count = $query->count();
        $resultMessage = '';

        $count = $query->count();
        $resultMessage = '';

        if ($count > 0) {
            if (preg_match('/^AD\d{8}$/', $search) || preg_match('/^NV\d{8}$/', $search)) {
                $resultMessage = "Tìm thấy {$count} người dùng có mã: {$search}";
            } else {
                $resultMessage = "Tìm thấy {$count} người dùng có tên chứa từ khóa: {$search}";
            }
        } else {
            if (preg_match('/^AD\d{8}$/', $search) || preg_match('/^NV\d{8}$/', $search)) {
                $resultMessage = "Không tìm thấy người dùng có mã: {$search}";
            } else {
                $resultMessage = "Không tìm thấy người dùng có tên chứa từ khóa: {$search}";
            }
        }


        $employees = $query->select('employee.*', 'user.*', 'role.description')
            ->orderBy('employee.employee_id')
            ->paginate(3)->appends($request->all());

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'employees', 'search', 'count', 'resultMessage'));
    }




    public function create()
    {
        $template = 'admin.permission.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        // Sinh employee_id và username ngẫu nhiên cho admin
        $randomIdAD = 'AD' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        while (Employee::where('employee_id', $randomIdAD)->exists()) {
            $randomIdAD = 'AD' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
        $randomUserNameAD = 'admin' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        while (User::where('username', $randomUserNameAD)->exists()) {
            $randomUserNameAD = 'admin' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
        // Sinh employee_id và username ngẫu nhiên cho nhân viên
        $randomIdEM = 'NV' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        while (Employee::where('employee_id', $randomIdEM)->exists()) {
            $randomIdEM = 'NV' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
        $randomUserNameEM = 'support' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        while (User::where('username', $randomUserNameEM)->exists()) {
            $randomUserNameEM = 'support' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'randomIdAD', 'randomUserNameAD', 'randomIdEM', 'randomUserNameEM'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employee,email', 'unique:customer,email'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'phone' => ['required', 'digits_between:9,11'],
            'address' => ['required', 'string', 'max:255'],
            'profile_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif'],
        ], [
            'full_name.max' => 'Tên nhân viên không được vượt quá 225 kí tự',
            'email.unique' => 'Email đã tồn tại',
            'date_of_birth.before_or_equal' => 'Ngày sinh phải đủ 18 tuổi',
            'phone.digits_between' => 'Số điện thoại phải có độ dài từ 9 đến 11 số',
            'address.max' => 'Địa chỉ không được vượt quá 225 kí tự',
            'profile_image.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, hoặc gif',
        ]);
        //Sinh user_id ngẫu nhiên
        $randomUserId = 'TK' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        while (User::where('user_id', $randomUserId)->exists()) {
            $randomUserId = 'TK' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        }

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
        $user->role_id = $request->input('role_id');
        $user->status = "active";
        $user->create_at = now();
        $user->update_at = now();
        $user->save();

        // Tạo nhân viên mới
        $employee = new Employee();
        $employee->employee_id = $request->input('employee_id');
        $employee->user_id = $randomUserId;
        $employee->full_name = preg_replace('/\s+/', ' ', trim($request->input('full_name')));
        $employee->email = $request->input('email');
        $employee->date_of_birth = $request->input('date_of_birth');
        $employee->gender = $request->input('gender');
        $employee->phone = $request->input('phone');
        $employee->address = preg_replace('/\s+/', ' ', trim($request->input('address')));
        $employee->profile_image = $profileImagePath;
        $employee->create_at = now();
        $employee->update_at = now();
        $employee->save();

        // Gửi email thông báo
        Mail::to($employee->email)->send(new EmployeeCreatedMail($employee, $user->user_id, $user->username, $password));

        return redirect()->route('permission.index')
            ->with('success', "Người dùng đã được thêm thành công và email đã được gửi! Tài khoản: $user->username. Mật khẩu: $password");
    }


    public function editPermission($employee_id)
    {
        $template = 'admin.permission.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $employee = Employee::with(['user.role'])
            ->where('employee_id', '=', $employee_id)
            ->first();
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'employee'));
    }

    // Cập nhật thông tin nhân viên
    public function updatePermission(Request $request, $employee_id)
    {
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
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->update_at = now();
        // Lưu thông tin nhân viên vào database
        $employee->save();
        $user->save();

        return redirect()->route('permission.index')
            ->with('success', 'Thông tin tài khoản đã được cập nhật!');
    }

    public function deletePermission($id)
    {
        // Xóa Employee và User liên quan
        Employee::where('user_id', $id)->delete();
        User::where('user_id', $id)->delete();

        return redirect()->route('permission.index')->with('success', 'Tài khoản đã được xóa thành công.');
    }
}
