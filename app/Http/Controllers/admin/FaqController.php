<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;

class FaqController extends Controller
{
    // Hiển thị danh sách FAQ
    public function index(Request $request)
    {
        $template = 'admin.faq.index';
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        // Lọc câu hỏi theo tìm kiếm và trạng thái
        $faqs = Faq::when($search, function ($query) use ($search) {
            return $query->where('question', 'LIKE', "%$search%");
        })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->paginate(4);

        $statuses = ['Đã phản hồi', 'Chưa phản hồi'];

        return view('admin.dashboard.layout', compact('template', 'faqs', 'statuses'));
    }

    // Hiển thị form tạo FAQ
    public function create()
    {
        $template = 'admin.faq.create';

        do {
            $randomNumber = mt_rand(1, 9999);
            $nextId = 'FAQ' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $exists = FAQ::where('faq_id', $nextId)->exists();
        } while ($exists);

        return view('admin.dashboard.layout', compact('template', 'nextId'));
    }

    // Lưu FAQ mới
    public function store(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|unique:faq,faq_id',
            'email' => 'required|email',
            'question' => 'required|max:255',
            'answer' => 'required',
            'status' => 'required|in:Đã phản hồi,Chưa phản hồi',
        ]);

        Faq::create($request->all());

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được thêm thành công!');
    }

    // Chỉnh sửa FAQ
    public function edit($faq_id)
    {
        $template = 'admin.faq.edit';
        $faq = Faq::findOrFail($faq_id);
        return view('admin.dashboard.layout', compact('template', 'faq'));
    }

    // Cập nhật FAQ
    public function update(Request $request, $faq_id)
    {
        $faq = FAQ::findOrFail($faq_id);

        $request->validate([
            'email' => 'required|email',
            'question' => 'required|max:255',
            'answer' => 'required',
            'status' => 'required|in:Đã phản hồi,Chưa phản hồi',
        ]);

        $faq->update($request->all());

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được cập nhật!');
    }

    // Xóa FAQ
    public function destroy($faq_id)
    {
        $faq = Faq::findOrFail($faq_id);
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'Câu hỏi đã được xóa!');
    }
}
