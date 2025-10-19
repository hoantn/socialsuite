
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPageUser extends Model
{
    protected $table = 'facebook_page_user';
    protected $fillable = ['facebook_user_id','facebook_page_id','page_access_token','scopes'];
    protected $casts = ['scopes' => 'array'];
}
