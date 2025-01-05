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
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
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
                // Lấy thông tin tài khoản từ cookie, nếu có
                $accounts = Cookie::get('accounts', []);

                // Nếu tài khoản là chuỗi, giải mã JSON, nếu không thì $accounts đã là mảng
                $accounts = is_string($accounts) ? json_decode($accounts, true) : $accounts;

                // Nếu không có tài khoản trong cookie, khởi tạo mảng rỗng
                $accounts = $accounts ?: [];

                // Lấy thông tin tài khoản hiện tại của người dùng đăng nhập
                $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

                // Kiểm tra tài khoản đã có trong cookie chưa
                $existingAccount = collect($accounts)->firstWhere('username', $request->username);

                // Nếu tài khoản chưa có trong cookie, thêm vào cookie
                if (!$existingAccount) {
                    $account = [
                        'customer_id' => $logged_user->customer_id,
                        'full_name' => $logged_user->full_name,
                        'profile_image' => $logged_user->profile_image,
                        'username' => $request->username,
                    ];

                    // Nếu có chọn "Remember me", lưu thêm password
                    if ($request->remember) {
                        $account['password'] = $request->password;
                    }

                    // Thêm tài khoản vào danh sách tài khoản trong cookie
                    $accounts[] = $account;

                    // Cập nhật cookie
                    Cookie::queue('accounts', json_encode($accounts), 60 * 24 * 30);  // Lưu trong 30 ngày
                } else {
                    // Nếu tài khoản đã có trong cookie và có chọn "Remember me", lưu thêm password vào cookie
                    if ($request->remember) {
                        // Tìm và cập nhật mật khẩu của tài khoản trong mảng
                        foreach ($accounts as &$account) {
                            if ($account['username'] === $request->username) {
                                $account['password'] = $request->password;
                                break;
                            }
                        }
                    }
                    Cookie::queue('accounts', json_encode($accounts), 60 * 24 * 30);  // Lưu trong 30 ngày
                }

                // Trả về trang chủ sau khi đăng nhập thành công
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
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique' => 'Email đã tồn tại.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        // Tạo ID ngẫu nhiên cho người dùng theo định dạng NDxxxxxxx
        $randUserID = (string)Str::uuid();

        // Tạo tài khoản người dùng
        $user = new User();
        $user->user_id = $randUserID;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role_id = 3;
        $user->save();

        // Tạo ID ngẫu nhiên cho khách hàng
        $randomId = (string) Str::uuid();

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

        return redirect()->route('homepage.index')->with('success', 'Đăng ký thành công! Hãy chờ kích hoạt tài khoản.');
    }
    // Quên mật khẩu
    public function forgotPass()
    {
        return view('login.forgot_pass');
    }
    // Xử lý form quên mật khẩu
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
            // Xóa dữ liệu OTP cũ nếu có
            $user->user->otp = null;
            $user->user->otp_expiration_time = null;
            $user->user->save();

            $randomOtp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $user->user->otp = $randomOtp;
            $user->user->otp_expiration_time = Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(5)->toDateTimeString();
            $user->user->save();

            // Gửi email thông báo
            Mail::to($email)->send(new VerifyEmail($user, $randomOtp));

            // Xóa countdown trong localStorage
            echo "<script>localStorage.removeItem('otpCountdown');</script>";

            // Chuyển hướng đến trang xác thực OTP
            return redirect()->route('verifyOTP', $user->user->user_id);
        }

        // Nếu email không tồn tại trong bảng nào
        return back()->with('error', 'Email không đúng/không tồn tại.');
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

        // Check OTP expiration
        $otpExpirationTime = Carbon::parse($user->otp_expiration_time)->addMinutes(1);
        if (Carbon::now('Asia/Ho_Chi_Minh')->greaterThan($otpExpirationTime)) {
            // Clear the OTP and expiration time
            $user->otp = null;
            $user->otp_expiration_time = null;
            $user->save();

            return back()->with('otp', 'Mã OTP đã hết hiệu lực.');
        }

        // Verify OTP
        if ($user->otp != $otp) {
            return back()->with('otp', 'Mã OTP không chính xác.');
        }

        // Clear OTP after successful verification
        $user->otp = null;
        $user->otp_expiration_time = null;
        $user->save();

        // Redirect to password change page
        return redirect()->route('changePass', $user->user_id);
    }
    // Phương thức trong controller
    public function deleteOtp(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $user->otp = null;
        $user->otp_expiration_time = null;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function changePass($user_id)
    {
        return view('login.change_pass', compact('user_id'));
    }
    public function updatePass(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

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
            return back()->withErrors(['old-password' => 'Mật khẩu cũ không đúng!'])->withInput();
        }

        $user->password = Hash::make($request->input('new-password'));
        $user->update_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    // Ví dụ trong Laravel
    public function checkEmail(Request $request) {
        $email = $request->input('email');
        $exists = Customer::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }
}
