<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id','direction','type','payload','sent_at'];
    protected $casts = ['payload'=>'array','sent_at'=>'datetime'];
}
