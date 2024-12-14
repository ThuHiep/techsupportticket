<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.faq.index';
        $logged_user = Auth::user();
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $date = $request->input('date');
        // Lọc câu hỏi
        $faqs = Faq::when($search, function ($query) use ($search) {
            if (str_starts_with($search, 'FAQ')) {
                return $query->where('faq_id', 'LIKE', "%$search%");
            } else {
                return $query->where('question', 'LIKE', "%$search%");
            }
        })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('create_at', $date);
            })
            ->paginate(4);
    

        // Đếm số lượng kết quả tìm thấy
        $totalResults = $faqs->total();

        $statuses = ['Đã phản hồi', 'Chưa phản hồi'];
        $isSearchById = $search && str_starts_with($search, 'FAQ');

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'faqs', 'statuses', 'search', 'totalResults', 'isSearchById'));
    }


    public function create()
    {
        $template = 'admin.faq.create';
        $logged_user = Auth::user();
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
        $logged_user = Auth::user();
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
}
