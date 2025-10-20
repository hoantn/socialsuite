<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbAccount extends Model {
    use HasFactory;
    protected $fillable = [
        'fb_user_id','name','avatar_url','user_access_token','token_expires_at','granted_scopes'
    ];
    protected $casts = [
        'token_expires_at' => 'datetime',
        'granted_scopes' => 'array',
    ];

    public function pages() {
        return $this->belongsToMany(FbPage::class, 'account_page', 'fb_account_id', 'page_id', 'id', 'page_id');
    }
}
