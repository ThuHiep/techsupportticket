<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestHistory extends Model
{
    // Tên bảng
    protected $table = 'requesthistory';

    // Khóa chính
    protected $primaryKey = 'history_id';

    // Tắt chế độ tự động tăng (vì khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'history_id',
        'request_id',
        'changed_by',
        'old_status',
        'new_status',
        'note',
        'changed_at',
    ];

    // Tắt timestamps mặc định (sử dụng cột `changed_at` tùy chỉnh)
    public $timestamps = false;

    // Định nghĩa quan hệ với bảng Request
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }

    // Định nghĩa quan hệ với bảng Employee (người thực hiện thay đổi)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'changed_by', 'employee_id');
    }
}
