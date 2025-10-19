
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = ['provider','type','payload'];
    protected $casts = ['payload'=>'array'];
}
