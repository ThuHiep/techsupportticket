<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login()
    {
        return view('login.login');
    }
    public function loginProcess(Request $request)
    {
        //  $request->validate([
        //      'username' => 'required',
        //      'password' => 'required',
        //      'g-recaptcha-response' => 'required', // Ensure reCaptcha is filled
        //  ]);

        //  $recaptchaResponse = $request->input('g-recaptcha-response');
        //  $secretKey = env('6Lcrz4kqAAAAAOljTaUh9OaofqlL1AUBZeOsKn9r'); // Store your secret key in .env

        //  // Verify reCaptcha response with Google
        //  $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //      'secret' => $secretKey,
        //      'response' => $recaptchaResponse,
        //  ]);

        //  $responseBody = $response->json();

        //  if (!$responseBody['success']) {
        //      return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);
        //  }

        // Kiểm tra mật khẩu
        //$password = $request->input('password');
//        if (strlen($password) > 5) {
//            return back()->withErrors(['password' => 'Mật khẩu không được ngắn quá 10 ký tự!']);
//        } elseif (!preg_match('/[A-Z]/', $password)) {
//            return back()->withErrors(['password' => 'Mật khẩu phải có ít nhất một chữ cái viết hoa!']);
//        } elseif (!preg_match('/[\W_]/', $password)) {
//            return back()->withErrors(['password' => 'Mật khẩu phải có ít nhất một kí tự đặc biệt!']);
//        }

        // Thực hiện đăng nhập
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            // Phân quyền người dùng
            $user = Auth::user();
            if ($user->role_id == 1 || $user->role_id == 2) {
                // Đối với Admin hoặc Quản lý
                return redirect()->route('dashboard.index')->with('success', "Chào mừng {$user->full_name} đến với trang quản trị");
            } elseif ($user->role_id == 3) {
                // Đối với Khách hàng
                return redirect()->route('indexAccount')->with('success', "Chào mừng {$user->full_name} đến với trang khách hàng");
            } else {
                return back()->with('error', 'Không tìm thấy vai trò của bạn.');
            }
        }

        return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng!']);
    }
    public function Logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
    //Đăng kí tài khoản customer
    public function register()
    {
        return view('login.register');
    }
    // Xử lý form đăng kí tài khoản
    public function registerProcess(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:user,username',
            'email' => 'required|email|unique:customer,email',
        ], [
            'required' => ':attribute là bắt buộc.',
            'email.unique' => 'Email đã tồn tại',
        ]);

        // Tạo ID ngẫu nhiên cho người dùng theo định dạng NDxxxxxxx
        $randUserID = 'ND' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        // Tạo tài khoản người dùng
        $user = new User();
        $user->user_id = $randUserID;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role_id = 3;
        $user->save();

        // Tạo ID ngẫu nhiên cho khách hàng
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        // Tạo khách hàng
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->user_id = $randUserID;
        $customer->full_name = $request['full_name'];
        $customer->date_of_birth = $request['date_of_birth'] ?? null;
        $customer->gender = $request['gender'] ?? null;
        $customer->phone = $request['phone'] ?? null;
        $customer->address = $request['address'] ?? null;
        $customer->email = $request['email'] ?? null;
        $customer->company = $request['company'] ?? null;
        $customer->create_at = now();
        $customer->update_at = now();
        $customer->save();

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy chờ kích hoạt tài khoản.');
    }
    // Quên mật khẩu
    public function forgotPass()
    {
        return view('login.forgot_pass');
    }
    // Xử lý form quên mật khẩu
    public function forgotPassProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $user = Customer::with('user')->where('email', $email)->first()
            ?? Employee::with('user')->where('email', $email)->first();

        if ($user) {
            $randomOtp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $user->user->otp = $randomOtp;
            $user->user->save();
            // Gửi email thông báo
            Mail::to($email)->send(new VerifyEmail($user, $randomOtp));

            // Chuyển hướng đến trang xác thực OTP
            return redirect()->route('verifyOTP', $user->user->user_id);
        }

        // Nếu không tìm thấy email trong cả hai bảng
        return back()->with('error', 'Email không tồn tại trong hệ thống.');
    }
    // Xác nhận otp gửi về email
    public function verifyOTP($user_id)
    {
        return view('login.verify_otp', compact('user_id'));
    }
    // Xử lý form xác nhận otp
    public function verifyOTPProcess(Request $request, $user_id)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = $request->input('otp');
        $user = User::findOrFail($user_id);

        if ($user->otp != $otp) {
            return back()->with('otp', 'Mã OTP không chính xác.');
        }

        return redirect()->route('changePass', $user->user_id);
    }
    public function changePass($user_id)
    {
        return view('login.change_pass', compact('user_id'));
    }
    public function updatePass(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        if ($request->input('new-password') !== $request->input('confirm-password')) {
            return back()->withErrors(['confirm-password' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->update_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
    // Thay đổi mật khẩu từ email
    public function changePassEmail($user_id)
    {
        return view('login.change_pass_email', compact('user_id'));
    }
    public function updatePassEmail(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (!Hash::check($request->input('old-password'), $user->password)) {
            return back()->withErrors(['old-password' => 'Mật khẩu cũ không đúng!']);
        }

        if ($request->input('new-password') !== $request->input('confirm-password')) {
            return back()->withErrors(['confirm-password' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->update_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
