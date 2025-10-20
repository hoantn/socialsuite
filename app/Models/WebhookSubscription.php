<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookSubscription extends Model {
    protected $fillable = ['page_id','verify_token','callback_url','fields'];
    protected $casts = ['fields' => 'array'];
}
