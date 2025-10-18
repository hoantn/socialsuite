<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * [SOCIALSUITE][GPT][2025-10-18 08:45 +07]
 * CHANGE: Bảng lưu long-lived user access token Facebook
 */
class FacebookToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','fb_user_id','fb_name','token','expires_at'];
    protected $dates = ['expires_at'];
}
