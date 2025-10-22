<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $fillable = [
        'page_id','page_name','message','media_path','timezone','publish_at','status','error_code','error_message','response'
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'response' => 'array',
    ];
}
