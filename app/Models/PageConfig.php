<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageConfig extends Model {
    protected $fillable = ['page_id','settings','updated_by'];
    protected $casts = ['settings' => 'array'];
}
