<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPost extends Model
{
    protected $fillable = [
        'page_id','post_id','message','type','status','response'
    ];

    protected $casts = [
        'response' => 'array',
    ];
}
