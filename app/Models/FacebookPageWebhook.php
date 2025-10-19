
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPageWebhook extends Model
{
    protected $fillable = ['facebook_page_id','subscription','active'];
}
