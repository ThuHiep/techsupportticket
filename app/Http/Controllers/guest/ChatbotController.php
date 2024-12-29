<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('guest.chatbot.index');
    }

    public function chat(Request $request)
    {
        // Xử lý tin nhắn từ người dùng
        $userMessage = $request->input('message');
        $response = $this->getResponse($userMessage); // Hàm này sẽ xử lý logic phản hồi
        return response()->json(['response' => $response]);
    }

    private function getResponse($message)
    {
        // Chuyển đổi câu hỏi thành chữ thường để so sánh
        $message = strtolower(trim($message));

        // Kiểm tra các câu chào
        $greetings = ['xin chào', 'chào', 'hello', 'hi', 'chào bạn'];

        foreach ($greetings as $greeting) {
            if (strpos($message, $greeting) !== false) {
                return 'Xin chào bạn! Tôi có thể giúp gì cho bạn không?';
            }
        }

        // Tìm kiếm trong bảng FAQ
        $faq = FAQ::where('question', 'LIKE', '%' . $message . '%')->first();

        if ($faq) {
            return $faq->answer; // Trả về câu trả lời từ bảng FAQ
        }

        return 'Xin lỗi, tôi không hiểu.';
    }

    public function getFAQs()
    {
        return FAQ::all();
    }
}
