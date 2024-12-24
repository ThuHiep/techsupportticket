<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'department';

    // Khóa chính
    protected $primaryKey = 'department_id';

    // Tắt chế độ tự động tăng (do khóa chính là `varchar`)
    public $incrementing = false;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    // Cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'department_id',
        'department_name',
        'status',
    ];

    // Tắt timestamps nếu bảng không có cột created_at và updated_at
    public $timestamps = false;

    // Định nghĩa trạng thái là enum
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * Lấy danh sách các phòng ban đang hoạt động.
     */
    public static function getActiveDepartments()
    {
        return self::where('status', self::STATUS_ACTIVE)->get();
    }
    public function requests()
    {
        return $this->hasMany(Request::class, 'department_id','department_id'); // Adjust 'department_id' if your foreign key is named differently
    }
}
