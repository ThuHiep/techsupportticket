<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer'; // The name of the table in the database
    protected $primaryKey = 'customer_id'; // The primary key field
    public $timestamps = true; // Enable timestamps for created_at and updated_at

    protected $keyType = 'string';  // Đảm bảo rằng customer_id không được chuyển sang kiểu số nguyên

    // Define custom timestamp names
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = [
        'customer_id',
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'profile_image',
        'company',
        'tax_id',
        'create_at',
        'update_at',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
    // Define any relationships with other models (if applicable)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'customer_id');
    }


}
