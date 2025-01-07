<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    /**
     * Tải xuống file đính kèm.
     */
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Kiểm tra quyền truy cập nếu cần (ví dụ: người dùng phải là người tạo yêu cầu)
        // ...

        // Kiểm tra nếu file tồn tại
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()->back()->with('error', 'File không tồn tại.');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->filename);
    }

}
