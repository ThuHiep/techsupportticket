<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    protected $keyType = 'string';  // Đảm bảo rằng customer_id không được chuyển sang kiểu số nguyên

    protected $table = 'user'; // Đảm bảo tên bảng chính xác
    protected $primaryKey = 'user_id'; // Đặt khóa chính nếu khác `id`

    protected $fillable = [
        'user_id', 'username', 'password', 'email', 'create_at',
        'otp', 'otp_expiration_time', 'otp_validation', 'status'
    ];
    public function customer()
    {
        return $this->hasMany(Customer::class, 'user_id');
    }
}
