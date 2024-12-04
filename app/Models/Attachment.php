<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    // Tên bảng
    protected $table = 'attachment';

    // Khóa chính
    protected $primaryKey = 'attachment_id';

    // Tắt chế độ tự động tăng (vì khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'attachment_id',
        'request_id',
        'filename',
        'fileimg',
        'file_path',
        'file_size',
        'file_type',
        'created_at',
    ];

    // Tắt timestamps mặc định (sử dụng cột `created_at` tùy chỉnh)
    public $timestamps = false;

    // Định nghĩa quan hệ với bảng Request
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }
}
