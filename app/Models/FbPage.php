<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    use HasFactory;

    protected $table = 'fb_pages';

    protected $fillable = [
        'owner_id',
        'page_id',
        'name',
        'category',
        'picture_url',
        'connected_ig_id',
        'raw',
    ];

    protected $casts = [
        'raw' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(FbUser::class, 'owner_id');
    }

    public function tokens()
    {
        return $this->hasMany(FbPageToken::class, 'page_id', 'page_id');
    }
}
