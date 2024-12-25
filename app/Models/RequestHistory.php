<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
class RequestHistory extends Model
{
    protected $table = 'requesthistory';
    protected $primaryKey = 'history_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'history_id',
        'request_id',
        'changed_by',
        'old_status',
        'new_status',
        'note',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->history_id)) {
                $model->history_id = self::generateHistoryId();
                Log::info("Generated history_id: " . $model->history_id);
            }
        });
    }

    /**
     * Tạo history_id theo định dạng HID + 5 số
     *
     * @return string
     */
    public static function generateHistoryId()
    {
        do {
            // Tạo 5 số ngẫu nhiên từ 00000 đến 99999
            $randomNumber = mt_rand(0, 99999);
            $historyId = 'HID' . str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
        } while (self::where('history_id', $historyId)->exists());

        return $historyId;
    }

    // Quan hệ với bảng Request
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    // Quan hệ với bảng Employee (changed_by)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'changed_by');
    }
}
