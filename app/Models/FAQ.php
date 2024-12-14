<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'faq';

    // Khóa chính
    protected $primaryKey = 'faq_id';

    // Tắt chế độ tự động tăng (do khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'faq_id',
        'email',
        'employee_id',
        'question',
        'answer',
        'create_at',
        'status'
    ];


    // Tắt timestamps (do bảng không có cột `created_at` và `updated_at`)
    public $timestamps = false;

    // Chuyển đổi kiểu dữ liệu cho cột
    protected $casts = [
        'create_at' => 'datetime',
    ];

    public function setAnswerAttribute($value)
    {
        $this->attributes['answer'] = $value ?: null; // Gán NULL nếu giá trị trống
    }

    /**
     * Định nghĩa quan hệ với bảng `employee`.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
