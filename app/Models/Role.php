<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property string $role_id
 * @property string $role_name
 * @property string|null $description
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'role_name',
        'description'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
