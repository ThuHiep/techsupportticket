<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchedUser extends Model
{
    use HasFactory;
    protected $table = 'switched_users';

    // Khai báo khóa chính
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['customer_id', 'username', 'password'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
