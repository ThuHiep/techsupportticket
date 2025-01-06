<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerFeedback;
use App\Models\Employee;
use App\Models\EmployeeFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Request as SupportRequest;
use App\Models\SwitchedUser;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

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
        $accounts = Cookie::get('accounts', '[]');
        $accounts = is_string($accounts) ? json_decode($accounts, true) : $accounts;
        $accounts = $accounts ?: [];

        // Lấy tài khoản hiện tại đã đăng nhập
        $loggedID = $logged_user->customer_id;
        // Loại bỏ tài khoản đang đăng nhập khỏi danh sách
        $accounts = collect($accounts)->reject(function ($account) use ($loggedID) {
            return $account['customer_id'] == $loggedID;
        })->values()->toArray();

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

            // Lấy thông tin tài khoản từ cookie, nếu có
            $accounts = Cookie::get('accounts', []);

            // Nếu tài khoản là chuỗi, giải mã JSON, nếu không thì $accounts đã là mảng
            $accounts = is_string($accounts) ? json_decode($accounts, true) : $accounts;

            // Tìm và cập nhật mật khẩu của tài khoản trong mảng
            foreach ($accounts as &$account) {
                if ($account['customer_id'] == $logged_user->customer_id) {
                    $account['profile_image'] = $profileImagePath;
                    break;
                }
            }

            Cookie::queue('accounts', json_encode($accounts), 60 * 24 * 30);  // Lưu trong 30 ngày
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

        $logged_user->user->username = $request['username'];
        $logged_user->user->save();

        return redirect()->route('indexAccount')
            ->with('success', 'Hồ sơ khách hàng đã được cập nhật!');
    }
    public function checkUsernameCustomer($username)
    {
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        if ($username == $logged_user->user->username) {
            return response()->json(['exists' => false]);
        } else {
            $exists = User::where('username', $username)->exists();

            return response()->json(['exists' => $exists]);
        }
    }

    public function checkEmailCustomer($email)
    {
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        if ($email == $logged_user->email) {
            return response()->json(['exists' => false]);
        } else {
            // Kiểm tra email trong bảng employee và customer
            $employeeExists = Employee::where('email', $email)->exists();
            $customerExists = Customer::where('email', $email)->exists();

            return response()->json([
                'exists' => $employeeExists || $customerExists,
            ]);
        }
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

    public function switchAccount(Request $request, $username)
    {
        // Lấy thông tin tài khoản từ cookie
        $accounts = json_decode(Cookie::get('accounts', '[]'), true);

        // Tìm tài khoản trong cookie
        $account = collect($accounts)->firstWhere('username', $username);

        if ($account) {
            if (isset($account['password']) && !empty($account['password'])) {
                // Đăng nhập với mật khẩu từ cookie
                $credentials = [
                    'username' => $account['username'],
                    'password' => $account['password'],
                ];

                if (Auth::attempt($credentials)) {
                    return redirect()->route('indexAccount')->with('success', "Chào mừng {$account['full_name']} đến với trang khách hàng");
                }
            } else {
                session(['login_username' => $account['username']]);
                // Nếu không có mật khẩu, chuyển hướng đến trang login
                return redirect()->route('login');
            }
        }

        // Nếu không tìm thấy tài khoản, chuyển hướng về trang login
        return redirect()->route('login')->with('error', 'Không tìm thấy tài khoản');
    }

    public function removeAccount($customer_id)
    {
        // Lấy danh sách tài khoản từ cookie
        $accounts = json_decode(Cookie::get('accounts', '[]'), true);

        // Tìm và loại bỏ tài khoản cần xóa
        $accounts = collect($accounts)->reject(function ($account) use ($customer_id) {
            return $account['customer_id'] === $customer_id;
        })->values()->toArray();

        // Cập nhật lại cookie với danh sách tài khoản đã xóa
        Cookie::queue('accounts', json_encode($accounts), 60 * 24 * 30); // Lưu cookie trong 30 ngày

        // Quay lại trang chuyển đổi tài khoản
        return redirect()->route('indexAccount')->with('success', 'Tài khoản đã được xóa khỏi danh sách');
    }

    public function reply(Request $request, $request_id)
    {
        $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $customer_feedback = new CustomerFeedback();
        $customer_feedback->request_id = $request_id;
        $customer_feedback->customer_id = $logged_user->customer_id;
        $customer_feedback->message = $request->input('reply_content');
        $customer_feedback->Save();

        return back()->with('success', 'Phản hồi đã được gửi thành công!');
    }

    private function getFeedback($feedbackModel, $joinModel, $foreignKey, $request_id)
    {
        return $feedbackModel::select(
            "{$feedbackModel->getTable()}.id",
            "{$feedbackModel->getTable()}.request_id",
            "{$joinModel->getTable()}.full_name",
            "{$joinModel->getTable()}.profile_image",
            "{$feedbackModel->getTable()}.message",
            "{$feedbackModel->getTable()}.created_at",
            "user.role_id"
        )
            ->join($joinModel->getTable(), "{$joinModel->getTable()}.{$foreignKey}", '=', "{$feedbackModel->getTable()}.{$foreignKey}")
            ->join('user', 'user.user_id', '=', "{$joinModel->getTable()}.user_id")
            ->where("{$feedbackModel->getTable()}.request_id", $request_id);
    }

    public function getFeedbackByRequestId($request_id)
    {
        // Lấy feedback từ khách hàng
        $customerFeedbacks = $this->getFeedback(new CustomerFeedback(), new Customer(), 'customer_id', $request_id);

        // Lấy feedback từ nhân viên
        $employeeFeedbacks = $this->getFeedback(new EmployeeFeedback(), new Employee(), 'employee_id', $request_id);

        // Kết hợp feedback từ cả hai bảng
        $feedbacks = $customerFeedbacks
            ->unionAll($employeeFeedbacks->toBase())
            ->orderBy('created_at', 'desc')
            ->get();

        $latestFeedback = EmployeeFeedback::firstWhere('is_read', false);

        if ($latestFeedback) {
            $latestFeedback->is_read = true;
            $latestFeedback->save();
        }

        return response()->json(['feedbacks' => $feedbacks]);
    }
}
