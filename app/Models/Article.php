<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // Tên bảng
    protected $table = 'articles';

    // Khóa chính
    protected $primaryKey = 'article_id';

    // Tắt chế độ tự động tăng (do khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'article_id',
        'title',
        'content',
        'employee_id',
    ];

    // Tắt timestamps (bảng không sử dụng `created_at` và `updated_at` mặc định)
    public $timestamps = false;

    /**
     * Định nghĩa quan hệ với bảng `employee`.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
