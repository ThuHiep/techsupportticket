<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\FAQ;

class HomepageController extends Controller
{
    public function __construct(){

    }
    public function getFaqs()
    {
        // Lấy các câu hỏi có trạng thái "Đã phản hồi"
        return FAQ::where('status', 'Đã phản hồi')->orderBy('create_at', 'desc')->get();
    }

    public function login(){
        $faqs = $this->getFaqs();
        $articles = Article::all(); // Lấy tất cả bài viết
        return view('guest.homepage.index', compact('faqs', 'articles'));
    }

}
