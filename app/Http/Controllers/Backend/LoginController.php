<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required', // Ensure reCaptcha is filled
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('6Lcl14kqAAAAALLGdKil_qiIKaUJiSm9yXBaE4FU'); // Store your secret key in .env

        // Verify reCaptcha response with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);
        }

        // Process login here
        // Example: Authenticate user
        if (auth()->attempt($request->only('username', 'password'))) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'Invalid credentials.']);
    }
    public function showLoginForm()
    {
        return view('backend.auth.login'); // Trả về view form đăng nhập
    }

}
