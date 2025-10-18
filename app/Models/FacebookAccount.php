<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FacebookAccount extends Model
{
  use HasFactory;
  protected $fillable = ['fb_user_id','name','avatar_url','user_access_token','expires_at'];
  protected $casts = ['expires_at'=>'datetime'];
  public function memberships(){ return $this->hasMany(PageMembership::class); }
}