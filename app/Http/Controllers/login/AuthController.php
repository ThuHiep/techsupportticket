<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\CustomerCreated;
use App\Mail\Register;
use App\Mail\VerifyEmail;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\SwitchedUser;
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
        if (Auth::check()) {
            Session::flush();
            Auth::logout();
        }
        return view('login.login');
    }
    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required', // Ensure reCaptcha is filled
        ]);

        // Kiểm tra reCAPTCHA
        $secretKey = env('NOCAPTCHA_SECRET');
        $responseKey = $request->input('g-recaptcha-response');

        // Gửi yêu cầu xác thực reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $responseKey,
            'remoteip' => $request->ip(),
        ]);

        $responseBody = $response->json();
        // Kiểm tra kết quả xác thực reCAPTCHA
        if (!$responseBody['success']) {
            return back()->withErrors(['captcha' => 'Vui lòng xác minh CAPTCHA!']);
        }

        $maxAttempts = 5; // Số lần thử tối đa
        $lockoutTime = 30; // Thời gian khóa (giây)
        $attempts = session('login_attempts', 0); // Số lần thử hiện tại
        $lastAttemptTime = session('last_attempt_time', now()->toIsoString()); // Lần thử cuối cùng

        // Kiểm tra nếu người dùng đã bị khóa
        if ($attempts >= $maxAttempts) {
            $timeElapsed = abs(now()->diffInSeconds($lastAttemptTime));

            if (!($timeElapsed < $lockoutTime)) {
                // Nếu hết thời gian khóa, reset lại số lần thử và thời gian
                session()->forget(['login_attempts', 'last_attempt_time', 'remaining_time']);
                $attempts = 0;
            }
        }

        // Kiểm tra đăng nhập
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            // Xóa số lần thử khi đăng nhập thành công
            session()->forget(['login_attempts', 'last_attempt_time', 'remaining_time']);

            if (isset($request['remember']) && !empty($request['remember'])) {
                setcookie("username", $request['username'], time() + 3600);
                setcookie("password", $request['password'], time() + 3600);
            } else {
                setcookie("username", "");
                setcookie("password", "");
            }

            //Phân quyền người dùng
            $user = Auth::user();

            if ($user->status === null) {
                Auth::logout();
                return back()->with('error', 'Tài khoản của bạn chưa được kích hoạt.');
            }

            if ($user->role_id == 1 || $user->role_id == 2) {
                $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
                return redirect()->route('dashboard.index')->with('success', "Chào mừng {$logged_user->full_name} đến với trang quản trị");
            } elseif ($user->role_id == 3) {
                $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
                $exists = SwitchedUser::where('customer_id', $logged_user->customer_id)->exists();
                if (!$exists) {
                    if (isset($request['remember']) && !empty($request['remember'])) {
                        $account = new SwitchedUser();
                        $account->customer_id = $logged_user->customer_id;
                        $account->username = $request['username'];
                        $account->password = $request['password'];
                        $account->save();
                    } else {
                        $account = new SwitchedUser();
                        $account->customer_id = $logged_user->customer_id;
                        $account->username = $request['username'];
                        $account->save();
                    }
                } else {
                    if (isset($request['remember']) && !empty($request['remember'])) {
                        $account = SwitchedUser::where('customer_id', $logged_user->customer_id)->first();
                        $account->password = $request['password'];
                        $account->save();
                    }
                }
                return redirect()->route('indexAccount')->with('success', "Chào mừng {$logged_user->full_name} đến với trang khách hàng");
            } else {
                return back()->with('error', 'Không tìm thấy vai trò của bạn.')->withInput();
            }
        }

        // Xử lý nếu đăng nhập sai
        $attempts++;
        session([
            'login_attempts' => $attempts,
            'last_attempt_time' => now()->toIsoString()
        ]);

        $remainingAttempts = $maxAttempts - $attempts;

        if ($remainingAttempts > 0) {
            return back()->with('error', "Tên đăng nhập hoặc mật khẩu không đúng. Bạn còn $remainingAttempts lần thử.")->withInput();
        } else {
            session(['remaining_time' => $lockoutTime]);
            return back()->with('error', "Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau $lockoutTime giây.")->withInput();
        }
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
        $randUserID = 'TK' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

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
        // Check if email is available
        if (!empty($customer->email)) {
            // Send notification email
            Mail::to($customer->email)->send(new Register($customer));
        } else {
            return redirect()->route('login')->with('error', 'Email không hợp lệ.');
        }

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
