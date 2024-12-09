<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    const CREATED_AT = 'create_at';
    protected $keyType = 'string';
    const UPDATED_AT = 'update_at';
    protected $table = 'user'; // Đảm bảo tên bảng chính xác
    protected $primaryKey = 'user_id'; // Đặt khóa chính nếu khác `id`

    protected $fillable = [
        'user_id', 'username','role_id', 'password', 'email', 'create_at','update_at',
        'otp', 'otp_expiration_time', 'otp_validation', 'status'
    ];
    public function customer()
    {
        return $this->hasMany(Customer::class, 'user_id');
    }
}
