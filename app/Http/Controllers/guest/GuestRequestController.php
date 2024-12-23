<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as SupportRequest;
use App\Models\RequestType;
use App\Models\Attachment;
use Carbon\Carbon;

class GuestRequestController extends Controller
{
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
            do {
                $newRequestId = 'RQ' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            } while (SupportRequest::where('request_id', $newRequestId)->exists());

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

            // Xử lý file đính kèm nếu có
            if ($files && isset($files[$index])) {
                $file = $files[$index];
                $filePath = $file->store('attachments', 'public');

                // Tạo bản ghi file đính kèm
                Attachment::create([
                    'attachment_id' => uniqid('ATT_'),
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

