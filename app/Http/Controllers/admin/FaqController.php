<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\FaqFeedback;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.faq.index';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        // Lấy các tham số tìm kiếm
        $search = $request->input('search'); // Từ khóa tìm kiếm theo nội dung câu hỏi
        $statusFilter = $request->input('status'); // Trạng thái câu hỏi
        $date = $request->input('date'); // Ngày cụ thể (nếu có)

        // Tìm kiếm FAQ
        $faqs = FAQ::where('status', 'Chưa phản hồi') // Lọc chỉ câu hỏi chưa phản hồi
            ->when($search, function ($query) use ($search) {
                // Tìm kiếm theo nội dung câu hỏi
                return $query->where('question', 'LIKE', "%$search%");
            })
            ->when($date, function ($query) use ($date) {
                // Lọc theo ngày tạo
                return $query->whereDate('create_at', $date);
            })
            ->paginate(5);

        // Đếm số lượng kết quả tìm thấy
        $totalResults = $faqs->total();

        // Xác định các tiêu chí tìm kiếm
        $isSearchWithDate = $search && $date; // Cả từ khóa và ngày
        $isSearchPerformed = $search || $date;
        $isTodaySearch = $date === now()->toDateString();

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'faqs',
            'search',
            'date',
            'statusFilter',
            'totalResults',
            'isSearchWithDate',
            'isSearchPerformed',
            'isTodaySearch',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }


    public function create()
    {
        $template = 'admin.faq.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $nextId = (string) Str::uuid();

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'nextId',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|unique:faq,faq_id',
            'email' => 'required|email',
            'question' => 'required',
            'answer' => 'nullable',
        ]);
        $faq = new Faq();
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        if ($request->input('answer')) {
            $faq->faq_id = $request->input('faq_id');
            $faq->employee_id = $logged_user->employee_id;
            $faq->email = $request->input('email');
            $faq->question = $request->input('question');
            $faq->answer = $request->input('answer');
            $faq->status = 'Đã phản hồi';
        } else {
            $faq->faq_id = $request->input('faq_id');
            $faq->email = $request->input('email');
            $faq->question = $request->input('question');
            $faq->status = 'Chưa phản hồi';
        }
        $faq->create_at = now();
        $faq->save();

        // Send email
        Mail::to($request->input('email'))->send(new FaqFeedback($faq->question, $faq->answer));

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được thêm thành công và email đã được gửi!');
    }

    public function feedback($faq_id)
    {
        $template = 'admin.faq.feedback';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $faq = Faq::findOrFail($faq_id);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'faq',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function feedbackProcess(Request $request, $faq_id)
    {
        $faq = FAQ::findOrFail($faq_id);
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq->employee_id = $logged_user->employee_id;
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->status = 'Đã phản hồi';
        $faq->save();

        Mail::to($request->input('email'))->send(new FaqFeedback($faq->question, $faq->answer));

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được phản hồi!');
        // Gửi email thông báo
    }

    public function destroy($faq_id)
    {
        $faq = Faq::findOrFail($faq_id);
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được xóa!');
    }

    public function unansweredByDate(Request $request)
    {
        // Lấy ngày từ request, nếu không có thì lấy ngày hiện tại
        $date = $request->input('date', now()->toDateString());

        // Đếm số lượng câu hỏi chưa phản hồi theo ngày
        $count = FAQ::where('status', 'Chưa phản hồi')
            ->whereDate('create_at', $date)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getAnswer($faq_id)
    {
        $faq = FAQ::with('employee')->where("faq_id", "=", $faq_id)->first();

        if ($faq) {
            return response()->json([
                'success' => true,
                'employee' => 'Người trả lời: ' . $faq->employee->full_name,
                'question' => $faq->question,
                'answer' => 'Câu trả lời: ' . $faq->answer
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy câu trả lời.',
        ]);
    }


    // public function storeAjax(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'question' => 'required|string|max:1000',
    //     ], [
    //         'email.required' => 'Vui lòng nhập email.',
    //         'email.email' => 'Email không đúng định dạng.',
    //         'question.required' => 'Vui lòng nhập câu hỏi.',
    //     ]);

    //     try {
    //         $faq = new FAQ();
    //         $faq->faq_id = 'FAQ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    //         $faq->email = $request->input('email');
    //         $faq->question = $request->input('question');
    //         $faq->status = 'Chưa phản hồi';
    //         $faq->create_at = now();
    //         $faq->save();

    //         return response()->json(['success' => true, 'message' => 'Câu hỏi đã được gửi thành công!']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
    //     }
    // }
    public function storeAjax(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'question' => 'required|string|max:1000',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'question.required' => 'Vui lòng nhập câu hỏi.',
        ]);

        try {
            $faq = new FAQ();
            // Tạo mã FAQ ngẫu nhiên giống như trong create
            $faq->faq_id = (string) \Illuminate\Support\Str::uuid();
            $faq->email = $request->input('email');
            $faq->question = $request->input('question');
            $faq->status = 'Chưa phản hồi';
            $faq->create_at = now();
            $faq->save();

            return response()->json(['success' => true, 'message' => 'Câu hỏi đã được gửi thành công!', 'faq_id' => $faq->faq_id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }
}
