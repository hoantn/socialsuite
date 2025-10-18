<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookToken extends Model {
    use HasFactory;
    protected $fillable = ['user_id','fb_user_id','fb_name','token','expires_at'];
    protected $dates = ['expires_at'];
}
