<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'content', 'commentable_id', 'commentable_type'];

    protected $appends = [
        'is_user'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    function getIsUserAttribute()
    {
        return $this->commentable_type === "App\Models\User";
    }
}
