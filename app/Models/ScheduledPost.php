<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $guarded = [];

    protected $casts = [
        'publish_at'  => 'datetime',
        'media_paths' => 'array',
    ];
}
