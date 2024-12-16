<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property string $user_id
 * @property string $username
 * @property string $password
 * @property string $role_id
 * @property Carbon|null $create_at
 * @property Carbon|null $update_at
 * @property string|null $otp
 * @property Carbon|null $otp_expiration_time
 * @property bool|null $otp_validation
 * @property string|null $status
 *
 * @property Role $role
 * @property Collection|Customer[] $customers
 * @property Collection|Employee[] $employees
 * @property Collection|Requesthistory[] $requesthistories
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $casts = [
        'create_at' => 'datetime',
        'update_at' => 'datetime',
        'otp_expiration_time' => 'datetime',
        'otp_validation' => 'bool'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'username',
        'password',
        'role_id',
        'create_at',
        'update_at',
        'otp',
        'otp_expiration_time',
        'otp_validation',
        'status'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class,'user_id', 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class,);
    }

    public function requesthistories()
    {
        return $this->hasMany(Requesthistory::class, 'changed_by');
    }
}
