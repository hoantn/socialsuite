<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FbToken extends Model {
    protected $fillable = ['user_id','access_token','type','expires_at','meta'];
    protected $casts = ['meta'=>'array','expires_at'=>'datetime'];
}
