<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    // Cho phép mass assignment cho các cột cần lưu
    protected $fillable = [
        'user_id',
        'page_id',
        'channel',
        'name',
        'access_token',
        'perms',
        'token_expires_at',
        'subscribed',
    ];

    protected $casts = [
        'perms' => 'array',
        'subscribed' => 'boolean',
        'token_expires_at' => 'datetime',
    ];
}