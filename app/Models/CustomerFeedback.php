<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'customer_feedback';

    // Khóa chính
    protected $primaryKey = 'id';

    // Loại khóa chính (unsigned integer)
    public $incrementing = true;
    protected $keyType = 'int';


    // Các cột có thể được gán giá trị
    protected $fillable = [
        'request_id',
        'customer_id',
        'message',
    ];

    // Tắt timestamps nếu bảng không có các cột `updated_at`
    public $timestamps = true;

    // Định nghĩa mối quan hệ
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
