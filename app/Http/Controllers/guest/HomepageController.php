<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function getFaqs()
    {
        // Lấy các câu hỏi có trạng thái "Đã phản hồi"
        return FAQ::where('status', 'Đã phản hồi')->orderBy('create_at', 'desc')->get();
    }

    public function login()
    {
        $faqs = $this->getFaqs();
        $articles = Article::all(); // Lấy tất cả bài viết
        if (Auth::check()) {
            $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
            return view('guest.homepage.index', compact('logged_user', 'faqs', 'articles'));
        }
        return view('guest.homepage.index', compact('faqs', 'articles'));
    }
    public function showFormRequest()
    {
        return view('guest.request.request');
    }
}
