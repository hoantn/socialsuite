
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $fillable = ['page_id','name','category','raw'];
    protected $casts = ['raw' => 'array'];
}
