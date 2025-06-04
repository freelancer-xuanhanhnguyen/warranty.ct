<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'customer_id', 'code', 'purchase_dates', 'warranty_expired'];

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
}
