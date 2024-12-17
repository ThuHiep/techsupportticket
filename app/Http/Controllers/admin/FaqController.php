<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index(Request $request)
{
    $template = 'admin.faq.index';
    $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

    // Lấy các tham số tìm kiếm
    $search = $request->input('search'); // Từ khóa hoặc mã FAQ
    $statusFilter = $request->input('status'); // Trạng thái câu hỏi

    // Kiểm tra xem từ khóa là mã FAQ (định dạng FAQxxxx)
    $isSearchById = $search && preg_match('/^FAQ\d{4}$/', $search);

    // Tìm kiếm FAQ
    $faqs = Faq::when($isSearchById, function ($query) use ($search) {
        return $query->where('faq_id', $search);
    })
    ->when(!$isSearchById && $search, function ($query) use ($search) {
        return $query->where('question', 'LIKE', "%$search%");
    })
    ->when($statusFilter, function ($query) use ($statusFilter) {
        return $query->where('status', $statusFilter);
    })
    ->paginate(4);

    // Đếm số lượng kết quả tìm thấy
    $totalResults = $faqs->total();

    // Xác định các tiêu chí tìm kiếm
    $isSearchWithStatus = $search && $statusFilter; // Cả từ khóa và trạng thái
    $isSearchPerformed = $search || $statusFilter; // Có thực hiện tìm kiếm

    return view('admin.dashboard.layout', compact(
        'template',
        'logged_user',
        'faqs',
        'search',
        'statusFilter',
        'totalResults',
        'isSearchById',
        'isSearchWithStatus',
        'isSearchPerformed'
    ));
}



    public function create()
    {
        $template = 'admin.faq.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        do {
            $randomNumber = mt_rand(1, 9999);
            $nextId = 'FAQ' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $exists = FAQ::where('faq_id', $nextId)->exists();
        } while ($exists);

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'nextId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|unique:faq,faq_id',
            'email' => 'required|email',
            'question' => 'required|max:255',
            'answer' => 'nullable', // Cho phép trống
            'status' => 'required|in:Đã phản hồi,Chưa phản hồi',
        ]);

        $data = $request->all();
        $data['answer'] = $data['answer'] ?? null; // Gán NULL nếu không nhập
        $data['status'] = $data['answer'] ? 'Đã phản hồi' : 'Chưa phản hồi';

        Faq::create($data);

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được thêm thành công!');
    }

    public function edit($faq_id)
    {
        $template = 'admin.faq.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $faq = Faq::findOrFail($faq_id);
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'faq'));
    }

    public function update(Request $request, $faq_id)
    {
        $faq = FAQ::findOrFail($faq_id);

        $request->validate([
            'email' => 'required|email',
            'question' => 'required|max:255',
            'answer' => 'nullable', // Cho phép trống
            'status' => 'required|in:Đã phản hồi,Chưa phản hồi',
        ]);

        $data = $request->all();
        $data['answer'] = $data['answer'] ?? null; // Gán NULL nếu không nhập
        $data['status'] = $data['answer'] ? 'Đã phản hồi' : 'Chưa phản hồi';

        $faq->update($data);

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được cập nhật!');
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
        $faq = FAQ::find($faq_id);
        if ($faq) {
            return response()->json([
                'success' => true,
                'answer' => $faq->answer,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy câu trả lời.',
        ]);
    }



    


}
