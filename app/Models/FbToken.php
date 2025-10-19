<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbToken extends Model
{
    protected $fillable = ['page_id','user_id','access_token','expires_at','scopes'];
    protected $casts = ['expires_at'=>'datetime','scopes'=>'array'];
}
