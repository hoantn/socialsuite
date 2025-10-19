<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model {
  protected $fillable = ['user_id','provider','access_token','long_lived','expires_at'];
  protected $casts = ['long_lived'=>'boolean','expires_at'=>'datetime'];
}