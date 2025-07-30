<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['code', 'name', 'email', 'phone', 'birthday', 'gender', 'address'];

    const GENDER_OTHER = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    const GENDER = [
        self::GENDER_MALE => 'Nam',
        self::GENDER_FEMALE => 'Nữ',
        self::GENDER_OTHER => 'Khác',
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
