<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 * 
 * @property string $employee_id
 * @property string $user_id
 * @property string $full_name
 * @property Carbon $date_of_birth
 * @property string $phone
 * @property string $gender
 * @property string $address
 * @property string|null $profile_image
 * @property string $email
 * @property Carbon|null $create_at
 * @property Carbon|null $update_at
 * 
 * @property User $user
 * @property Collection|Article[] $articles
 * @property Collection|Faq[] $faqs
 *
 * @package App\Models
 */
class Employee extends Model
{
    protected $table = 'employee';
    protected $primaryKey = 'employee_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'date_of_birth' => 'datetime',
        'create_at' => 'datetime',
        'update_at' => 'datetime'
    ];

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'phone',
        'gender',
        'address',
        'profile_image',
        'email',
        'create_at',
        'update_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(EmployeeFeedback::class, 'employee_id', 'employee_id');
    }
}
