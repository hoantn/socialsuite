<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {
    protected $fillable = ['page_id', 'name', 'page_access_token', 'perms'];
    protected $casts = ['perms' => 'array'];
}
