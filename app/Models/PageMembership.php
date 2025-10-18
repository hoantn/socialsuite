<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PageMembership extends Model
{
  use HasFactory;
  protected $fillable=['facebook_account_id','facebook_page_id','page_access_token','perms','is_active','last_verified_at'];
  protected $casts=['perms'=>'array','is_active'=>'boolean','last_verified_at'=>'datetime'];
  public function account(){ return $this->belongsTo(FacebookAccount::class,'facebook_account_id'); }
  public function page(){ return $this->belongsTo(FacebookPage::class,'facebook_page_id'); }
}