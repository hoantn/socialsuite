<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPage extends Model {
    public $incrementing = false;
    protected $primaryKey = 'page_id';
    protected $keyType = 'string';
    protected $fillable = [
        'page_id','name','username','category','avatar_url','connected_ig_id',
        'page_access_token','token_expires_at','capabilities'
    ];
    protected $casts = [
        'token_expires_at' => 'datetime',
        'capabilities' => 'array',
    ];

    public function config() {
        return $this->hasOne(PageConfig::class, 'page_id', 'page_id');
    }
}
