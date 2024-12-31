<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Customer;
use App\Models\RequestType;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $articles = Article::all();

        if (Auth::check()) {
            if (Auth::user()->role_id == 3) {
                $logged_user = Customer::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
                return view('guest.homepage.index', compact('logged_user', 'faqs', 'articles'));
            } else {
                Session::flush();
                Auth::logout();
                return view('guest.homepage.index', compact('faqs', 'articles'));
            }
        }
        return view('guest.homepage.index', compact('faqs', 'articles'));
    }
    public function showFormRequest()
    {
        // Lấy thông tin khách hàng từ session đăng nhập
        $logged_user = Auth::user(); // Người dùng hiện tại
        $customer = Customer::where('user_id', $logged_user->user_id)->first();

        // Kiểm tra nếu khách hàng không tồn tại
        if (!$customer) {
            return redirect()->route('homepage.index')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        // Lấy danh sách các loại yêu cầu
        $requestTypes = RequestType::where('status', 'active')->get();

        // Truyền dữ liệu vào view
        return view('guest.request.request', compact('customer', 'requestTypes'));
    }

    public function Search(Request $request)
    {
        $keyword = $request->input('keyword');
        $type = $request->input('type');
        $results = "";

        if ($type === 'faq') {
            $results = FAQ::where('question', 'LIKE', "%{$keyword}%")
                ->whereNotNull('answer')
                ->where('answer', '<>', '')
                ->get();
        } elseif ($type === 'article') {
            $results = Article::where('title', 'LIKE', "%{$keyword}%")
                ->get();
        }

        return response()->json($results);
    }
}
