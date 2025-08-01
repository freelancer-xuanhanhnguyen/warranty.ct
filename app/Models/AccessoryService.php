<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryService extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id',
        'service_id',
        'total',
        'quantity'
    ];

    protected $casts = [
        'total' => 'float',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }
}
