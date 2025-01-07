<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as SupportRequest;
use App\Models\RequestType;
use App\Models\Attachment;
use Illuminate\Support\Str;
use App\Models\RequestHistory;

use Carbon\Carbon;

class GuestRequestController extends Controller
{


    public function getRequestStatus($requestId)
    {
        $history = RequestHistory::where('request_id', $requestId)
            ->orderBy('changed_at', 'asc')
            ->get(['changed_at as time', 'new_status as status']);

        return response()->json($history);
    }

    public function store(Request $request)
    {
        // Validate dữ liệu form
        $request->validate([
            'title' => 'required|array|min:1',
            'title.*' => 'required|string|max:255',
            'description' => 'required|array|min:1',
            'description.*' => 'required|string',
            'request-type' => 'required|exists:request_type,request_type_id',
            'attachments' => 'array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:40960', // 40 MB
        ]);

        // Lấy thông tin khách hàng từ session hoặc auth
        $customer = Customer::where('user_id', Auth::id())->first();

        // Kiểm tra nếu khách hàng không tồn tại
        if (!$customer) {
            return redirect()->route('showFormRequest')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        // Lấy request_type_id
        $requestTypeId = $request->input('request-type');

        // Lặp qua các yêu cầu (title/description)
        $titles = $request->input('title');
        $descriptions = $request->input('description');
        $files = $request->file('attachments'); // Có thể null

        foreach ($titles as $index => $title) {
            // Tạo request_id ngẫu nhiên không trùng
            $newRequestId = (string) Str::uuid();

            // Tạo yêu cầu mới
            $supportRequest = SupportRequest::create([
                'request_id' => $newRequestId,
                'customer_id' => $customer->customer_id,
                'request_type_id' => $requestTypeId,
                'department_id' => null, // Cho phép giá trị null
                'subject' => $title,
                'description' => $descriptions[$index],
                'create_at' => now(),
                'priority' => 'Thấp',
                'status' => 'Chưa xử lý',
            ]);

            // Tạo bản ghi lịch sử đầu tiên với trạng thái "Chưa xử lý"
            RequestHistory::create([
                'history_id' => (string)Str::uuid(),
                'request_id' => $newRequestId,
                'changed_by' => null, // Hoặc một giá trị mặc định nếu khách hàng không có ID nhân viên
                'old_status' => null,
                'new_status' => 'Chưa xử lý',
                'note' => 'Tạo yêu cầu',
                'changed_at' => now(),
            ]);

            // Xử lý file đính kèm nếu có
            if ($files && isset($files[$index])) {
                $file = $files[$index];
                $filePath = $file->store('attachments', 'public');

                // Tạo bản ghi file đính kèm
                Attachment::create([
                    'attachment_id' => (string)Str::uuid(), // Sử dụng phương thức mới
                    'request_id' => $supportRequest->request_id,
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'created_at' => now(),
                ]);
            }
        }

        // Redirect về form với thông báo thành công
        return redirect()->route('showFormRequest')->with('success', 'Yêu cầu của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm nhất!');
    }
}

