<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Tên bảng
    protected $table = 'role';

    // Khóa chính
    protected $primaryKey = 'role_id';

    // Tắt chế độ tự động tăng (vì khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'role_id',
        'role_name',
        'description',
    ];

    // Tắt timestamps mặc định (sử dụng cột `created_at` tùy chỉnh)
    public $timestamps = false;
}
