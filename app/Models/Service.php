<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{
    use HasFactory, LogsActivity, Notifiable;

    protected $fillable = ['order_id', 'repairman_id', 'code', 'type', 'content', 'fee_total', 'note', 'reception_date', 'expected_completion_date', 'evaluate', 'evaluate_note'];

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['id', 'order_id', 'created_at', 'updated_at'])
            ->dontSubmitEmptyLogs()
            ->useLogName('services');
    }

    protected static function booted(): void
    {
        static::addLogChange(new class implements LoggablePipe {
            public function handle(EventLogBag $event, \Closure $next): EventLogBag
            {
                unset($event->changes['old']);
                return $next($event);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(AccessoryService::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function repairman()
    {
        return $this->belongsTo(User::class, 'repairman_id')
            ->active()
            ->where('role', User::ROLE_REPAIRMAN);
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
            get: fn($value) => number_format($value, 0, '', ''),
            set: fn($value) => number_format((float)$value, 2, '.', ''),
        );
    }
}
