
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookUser extends Model
{
    protected $fillable = ['user_id','fb_user_id','access_token','profile'];
    protected $casts = ['profile' => 'array'];
}
