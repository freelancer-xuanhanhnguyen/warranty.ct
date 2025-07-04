<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'repairman_id', 'code', 'type', 'content', 'fee_total', 'fee_detail', 'reception_date', 'expected_completion_date', 'evaluate', 'evaluate_note'];

    protected $casts = [
        'fee_total' => 'float',
    ];

    const TYPE_REPAIR = 0;
    const TYPE_WARRANTY = 1;

    const TYPE = [
        self::TYPE_REPAIR => 'Sửa chữa',
        self::TYPE_WARRANTY => 'Bảo hành',
    ];

    const TYPE_CLASS = [
        self::TYPE_REPAIR => 'default',
        self::TYPE_WARRANTY => 'smooth',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function repairman()
    {
        return $this->belongsTo(User::class, 'repairman_id');
    }

    public function status()
    {
        return $this->hasOne(ServiceStatus::class)->latestOfMany();
    }

    public function statuses()
    {
        return $this->hasMany(ServiceStatus::class)->oldest();
    }

    protected function feeTotal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($value, 0, '', ''),
            set: fn ($value) => number_format((float) $value, 2, '.', ''),
        );
    }
}
