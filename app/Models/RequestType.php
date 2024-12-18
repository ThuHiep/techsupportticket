<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    // Tên bảng
    protected $table = 'request_type';

    // Khóa chính
    protected $primaryKey = 'request_type_id';

    // Tắt chế độ tự động tăng (vì khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'request_type_id',
        'request_type_name',
        'status',
    ];

    // Tắt timestamps (bảng không sử dụng `created_at` và `updated_at` mặc định)
    public $timestamps = false;
    public function requests()
    {
        return $this->hasMany(Request::class, 'request_type_id'); // Giả sử 'request_type_id' là khóa ngoại trong bảng 'requests'
    }
}
