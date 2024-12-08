<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    // Tên bảng
    protected $table = 'request';

    // Khóa chính
    protected $primaryKey = 'request_id';

    // Tắt chế độ tự động tăng (vì khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'request_id',
        'customer_id',
        'department_id',
        'request_type_id',
        'subject',
        'description',
        'received_at',
        'resolved_at',
        'priority',
        'status',
    ];

    // Tắt timestamps mặc định (sử dụng các cột `create_at` và `update_at` tùy chỉnh)
    public $timestamps = false;

    // Định nghĩa quan hệ với bảng Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Định nghĩa quan hệ với bảng Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    // Định nghĩa quan hệ với bảng RequestType
    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id', 'request_type_id');
    }
    public static function getStatusCounts() {
        return [
            'processing' => self::where('status', 'processing')->count(),
            'handled' => self::where('status', 'handled')->count(),
            'completed' => self::where('status', 'completed')->count(),
            'cancelled' => self::where('status', 'cancelled')->count(),
        ];
    }

}
