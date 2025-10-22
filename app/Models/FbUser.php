<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbUser extends Model
{
    use HasFactory;

    protected $table = 'fb_users';

    protected $fillable = [
        'fb_user_id',
        'name',
        'email',
        'picture_url',
        'access_token',
        'access_token_expires_at',
        'raw',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
        'raw' => 'array',
    ];

    public function pages()
    {
        return $this->hasMany(FbPage::class, 'owner_id');
    }
}
