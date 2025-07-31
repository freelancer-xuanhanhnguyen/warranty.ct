<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{

    const EVENTS = [
        'created' => "Tạo mới",
        'updated' => "Thay đổi",
        'deleted' => "Xóa",
    ];

    public function repairman()
    {
        return $this->belongsTo(
            User::class,
            'properties->repairman_id'
        );
    }
}
