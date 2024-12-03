<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user'; // Đảm bảo tên bảng chính xác
    protected $primaryKey = 'user_id'; // Đặt khóa chính nếu khác `id`

    protected $fillable = [
        'user_id', 'username', 'password', 'email',
        'otp', 'otp_expiration_time', 'otp_validation', 'status'
    ];

}
