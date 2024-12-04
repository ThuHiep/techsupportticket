<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'employee';

    // Khóa chính
    protected $primaryKey = 'employee_id';

    // Tắt chế độ tự động tăng (do khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'employee_id',
        'user_id',
        'full_name',
        'date_of_birth',
        'phone',
        'gender',
        'address',
        'profile_image',
    ];

    // Tắt timestamps mặc định (do bảng sử dụng `create_at` và `update_at` thay vì `created_at` và `updated_at`)
    public $timestamps = false;

    /**
     * Định nghĩa quan hệ với bảng `users`.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Định nghĩa phương thức để lấy tên file ảnh đại diện đầy đủ đường dẫn.
     */
//    public function getProfileImageUrl()
//    {
//        return $this->profile_image ? asset('storage/' . $this->profile_image) : null;
//    }
}
