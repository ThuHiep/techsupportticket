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

class EmployeeController extends Controller
{
    public function editProfile()
    {
        $template = 'admin.employee.editProfile';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        return view('admin.dashboard.layout', compact('template', 'logged_user'));
    }

    // Cập nhật thông tin hồ sơ
    public function updateProfile(Request $request)
    {
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        // Kiểm tra nếu có hình ảnh được upload
        $profileImagePath = $logged_user->profile_image;
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

        $logged_user->full_name = $request->input('full_name');
        $logged_user->date_of_birth = $request->input('date_of_birth');
        $logged_user->gender = $request->input('gender');
        $logged_user->phone = $request->input('phone');
        $logged_user->address = $request->input('address');
        $logged_user->profile_image = $profileImagePath;
        $logged_user->email = $request->input('email');
        $logged_user->update_at = now();

        $logged_user->user->username = $request->input('username');
        $logged_user->user->status = $request->input('status');
        $logged_user->user->update_at = now();

        $logged_user->save();
        $logged_user->user->save();

        return redirect()->route('dashboard.index')
            ->with('success', 'Thông tin tài khoản đã được cập nhật!');
    }
    public function changePass(Request $request)
    {
        $logged_user = Auth::user();

        if (!Hash::check($request->input('old-password'), $logged_user->password)) {
            return back()->withErrors(['old-password' => 'Mật khẩu cũ không đúng!'])
                ->withInput();
        }

        if ($request->input('new-password') !== $request->input('confirm-password')) {
            return back()->withErrors(['confirm-password' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!'])
                ->withInput();
        }

        // Lưu mật khẩu mới
        $logged_user->password = Hash::make($request->input('new-password'));
        $logged_user->update_at = now();
        $logged_user->save();
        return redirect()->route('employee.editProfile')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
