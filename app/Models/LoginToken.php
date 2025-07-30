<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    protected $fillable = ['email', 'token', 'expires_at'];

    // Xóa token đã hết hạn
    protected $dates = ['expires_at'];
}
