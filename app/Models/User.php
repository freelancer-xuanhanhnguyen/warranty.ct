<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'birthday',
        'gender',
        'address',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATE = 0;

    const STATUS = [
        self::STATUS_ACTIVE => 'Hoạt động',
        self::STATUS_DEACTIVATE => 'Không hoạt động',
    ];

    const STATUS_CLASS = [
        self::STATUS_ACTIVE => 'success',
        self::STATUS_DEACTIVATE => 'danger',
    ];

    const ROLE_ADMIN = 0;
    const ROLE_CSKH = 1;
    const ROLE_REPAIRMAN = 2;

    const ROLE = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_CSKH => 'CSKH',
        self::ROLE_REPAIRMAN => 'Kỹ thuật viên',
    ];

    const ROLE_CLASS = [
        self::ROLE_ADMIN => 'default',
        self::ROLE_CSKH => 'city',
        self::ROLE_REPAIRMAN => 'flat',
    ];

    const GENDER_OTHER = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    const GENDER = [
        self::GENDER_MALE => 'Nam',
        self::GENDER_FEMALE => 'Nữ',
        self::GENDER_OTHER => 'Khác',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

//    public function sendPasswordResetNotification($token)
//    {
//        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
//    }
}
