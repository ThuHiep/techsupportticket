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
        'create_at',
        'received_at',
        'resolved_at',
        'status',
    ];

    // Đặt giá trị mặc định cho các cột
    protected $attributes = [
        'status' => 'Chưa xử lý', // Đặt mặc định là "Chưa xử lý"
        'resolved_at' => null,    // Đặt mặc định là null
    ];

    // Định dạng ngày
    protected $casts = [
        'create_at' => 'datetime',
        'received_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Tắt timestamps mặc định (sử dụng các cột `create_at` và `update_at` tùy chỉnh)
    public $timestamps = false;

    // Định nghĩa quan hệ với bảng Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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

    /**
     * Quan hệ với Attachment (HasOne)
     */
    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'request_id', 'request_id');
    }

    public function history()
    {
        return $this->hasMany(RequestHistory::class, 'request_id', 'request_id');
    }
    public function feedbacks()
    {
        return $this->hasMany(CustomerFeedback::class, 'customer_id', 'customer_id');
    }


    public function employeeFeedbacks()
    {
        return $this->hasMany(EmployeeFeedback::class, 'request_id', 'request_id');
    }

    public function customerFeedbacks()
    {
        return $this->hasMany(CustomerFeedback::class, 'request_id', 'request_id');
    }
}
