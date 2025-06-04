<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'code'];

    const STATUS_WAITING = 0;
    const STATUS_UNDER_WARRANTY = 1;
    const STATUS_EXPIRED_WARRANTY = 2;
    const STATUS_UNDER_REPAIR = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELED = 5;

    const STATUS = [
        self::STATUS_WAITING => 'Đang chờ',
        self::STATUS_UNDER_WARRANTY => 'Đang bảo hành',
        self::STATUS_EXPIRED_WARRANTY => 'Hết bảo hành',
        self::STATUS_UNDER_REPAIR => 'Đang sửa chữa',
        self::STATUS_COMPLETED => 'Đã hoàn thành',
        self::STATUS_CANCELED => 'Đã hủy',
    ];

    const STATUS_CLASS = [
        self::STATUS_WAITING => 'warning',
        self::STATUS_UNDER_WARRANTY => 'primary',
        self::STATUS_EXPIRED_WARRANTY => 'danger',
        self::STATUS_UNDER_REPAIR => 'info',
        self::STATUS_COMPLETED => 'success',
        self::STATUS_CANCELED => 'secondary',
    ];
}
