<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    protected $fillable=['username','password','email','phone','is_admin','plan_id','plan_expires_at'];
    protected $hidden=['password','remember_token'];
}
