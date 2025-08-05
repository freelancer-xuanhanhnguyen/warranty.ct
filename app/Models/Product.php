<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'serial', 'warranty_period_unit', 'warranty_period', 'periodic_warranty_unit', 'periodic_warranty', 'repairman_id'];

    const WARRANTY_UNIT_DAY = 0;
    const WARRANTY_UNIT_MONTH = 1;
    const WARRANTY_UNIT_YEAR = 2;

    const WARRANTY_UNIT = [
        self::WARRANTY_UNIT_DAY => 'Ngày',
        self::WARRANTY_UNIT_MONTH => 'Tháng',
        self::WARRANTY_UNIT_YEAR => 'Năm',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function repairman()
    {
        return $this->belongsTo(User::class, 'repairman_id');
    }
}
