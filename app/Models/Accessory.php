<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'unit_price',
        'quantity'
    ];

    protected $casts = [
        'unit_price' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(AccessoryService::class);
    }
}
