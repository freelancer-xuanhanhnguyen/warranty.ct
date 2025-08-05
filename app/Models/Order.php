<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'customer_id', 'code', 'purchase_date', 'end_date', 'next_date', 'old_date'];

    protected $appends = [
        'expired'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'end_date' => 'date',
        'next_date' => 'date',
        'old_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->hasOne(Service::class)->latestOfMany();
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    function getExpiredAttribute()
    {
        return is_null($this->end_date) ? null : now()->toDateString() > $this->end_date->toDateString();
    }
}
