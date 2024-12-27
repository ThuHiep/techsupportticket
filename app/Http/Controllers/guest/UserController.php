<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Request as SupportRequest;
use App\Models\SwitchedUser;
use App\Models\User;

class UserController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $template = 'guest.user.index';
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        return view('guest.dashboard.layout', compact('template', 'logged_user'));
    }

    public function getUserList()
    {
        $users = Customer::select('customer_id', 'full_name', 'status')
            ->whereNull('status') // Chỉ lấy các tài khoản chưa được phê duyệt
            ->get();

        return response()->json($users);
    }

    public function indexAccount()
    {
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $accounts = SwitchedUser::with('customer')->where('customer_id', '!=', $logged_user->customer_id)->get();

        if (!$logged_user) {
            return redirect()->route('homepage.index')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        // Lấy lịch sử yêu cầu của khách hàng
        $requests = SupportRequest::where('customer_id', $logged_user->customer_id)
            ->with(['requestType', 'attachment', 'history'])
            ->orderBy('create_at', 'desc')
            ->get();

        return view('guest.account.index', compact('logged_user', 'accounts', 'requests'));
    }

    public function updateProfile(Request $request)
    {
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        // Kiểm tra nếu có hình ảnh được upload
        $profileImagePath = $logged_user->profile_image;
        if ($request->hasFile('profile_image')) {
            // Xóa ảnh cũ nếu có
            if ($profileImagePath && file_exists(public_path('admin/img/customer/' . $profileImagePath))) {
                unlink(public_path('admin/img/customer/' . $profileImagePath));
            }

            // Lưu ảnh mới
            $image = $request->file('profile_image');
            if ($image->isValid()) {
                $imageName = 'update_' . time() . '.' . $image->getClientOriginalExtension();
                $profileImagePath = $imageName;  // Cập nhật đường dẫn ảnh mới
                $image->move(public_path('admin/img/customer/'), $imageName);  // Di chuyển ảnh mới vào thư mục
            }
        }

        // Cập nhật thông tin khách hàng
        $logged_user->full_name = $request['full_name'];
        $logged_user->date_of_birth = $request['date_of_birth'];
        $logged_user->gender = $request['gender'];
        $logged_user->phone = $request['phone'];
        $logged_user->address = $request['address'];
        $logged_user->profile_image = $profileImagePath;
        $logged_user->software = $request['software'];
        $logged_user->website = $request['website'];
        $logged_user->company = $request['company'];
        $logged_user->email = $request['email'];
        $logged_user->tax_id = $request['tax_id'];
        $logged_user->update_at = now();
        $logged_user->save();

        return redirect()->route('indexAccount')
            ->with('success', 'Hồ sơ khách hàng đã được cập nhật!');
    }
    public function changePass(Request $request)
    {
        $logged_user = Auth::user();

        // Check if old password is correct
        if (!Hash::check($request->input('old-password'), $logged_user->password)) {
            return back()->withErrors(['old-password' => 'Mật khẩu cũ không đúng!'])->withInput();
        }

        // Update the password
        $logged_user->password = Hash::make($request->input('new-password'));
        $logged_user->update_at = now();
        $logged_user->save();

        return redirect()->route('indexAccount')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    public function switchAccount(Request $request, $id)
    {
        Auth::logout();
        $account = SwitchedUser::with('customer')->where('id', $id)->first();

        if ($account->password) {
            $newUser = User::where('user_id', $account->customer->user->user_id)->first();

            Auth::login($newUser);

            return redirect()->route('indexAccount')->with('success', "Chào mừng {$account->customer->full_name} đến với trang khách hàng");
        } else {

            return redirect()->route('login')->withInput(['username' => $account->username]);
        }
    }
}
