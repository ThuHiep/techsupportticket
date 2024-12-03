<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer'; //Đúng tên bảng trong CSDL
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'profile_image',
        'company',
        'tax_id',
    ];

}
