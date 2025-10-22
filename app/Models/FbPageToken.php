<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPageToken extends Model
{
    use HasFactory;

    protected $table = 'fb_page_tokens';

    protected $fillable = [
        'page_id',
        'access_token',
        'access_token_expires_at',
        'raw',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
        'raw' => 'array',
    ];
}
