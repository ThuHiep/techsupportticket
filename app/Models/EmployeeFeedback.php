<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFeedback extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'employee_feedback';

    // Khóa chính
    protected $primaryKey = 'id';

    // Loại khóa chính (unsigned integer)
    public $incrementing = true;
    protected $keyType = 'int';

    // Các cột có thể được gán giá trị
    protected $fillable = [
        'request_id',
        'employee_id',
        'message',
    ];

    // Tắt timestamps nếu bảng không có cột `updated_at`
    public $timestamps = false;

    // Định nghĩa mối quan hệ
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
