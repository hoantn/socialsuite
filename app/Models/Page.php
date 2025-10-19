<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['platform','page_id','name','avatar_url','category','connected'];
    protected $casts = ['connected'=>'boolean'];
}
