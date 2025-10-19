<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = ['page_id','topic','subscribed','verify_token'];
    protected $casts = ['subscribed'=>'boolean'];
}
