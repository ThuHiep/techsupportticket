<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
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

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
