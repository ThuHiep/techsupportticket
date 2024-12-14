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
        // $request->validate([
        //     'username' => 'required',
        //     'password' => 'required',
        //     'g-recaptcha-response' => 'required', // Ensure reCaptcha is filled
        // ]);

        // $recaptchaResponse = $request->input('g-recaptcha-response');
        // $secretKey = env('6Lcl14kqAAAAALLGdKil_qiIKaUJiSm9yXBaE4FU'); // Store your secret key in .env

        // // Verify reCaptcha response with Google
        // $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => $secretKey,
        //     'response' => $recaptchaResponse,
        // ]);

        // $responseBody = $response->json();

        // if (!$responseBody['success']) {
        //     return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);
        // }

        // Process login here
        // Example: Authenticate user
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                return redirect()->route('dashboard.index');
            } elseif (Auth::user()->role_id == 3) {
                return redirect()->route('homepage.index');
            } else {
                return back()->with('error', 'Error to find your role');
            }
        }

        return back()->withErrors(['login' => 'Invalid credentials.']);
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
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, // Role mặc định cho khách hàng
        ]);

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
