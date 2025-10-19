
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'provider_page_id','name','category','page_access_token','subscribed',
    ];

    protected $casts = ['subscribed'=>'bool'];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class)->withTimestamps();
    }

    public function configs()
    {
        return $this->hasMany(PageConfig::class);
    }
}
