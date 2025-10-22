<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPost extends Model
{
    protected $fillable = [
        'page_id','page_name','post_id','message','type','status','error_code','error_message','response'
    ];

    protected $casts = [
        'response' => 'array',
    ];
}
