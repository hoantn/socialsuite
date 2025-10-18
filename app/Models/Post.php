<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Post extends Model
{
  use HasFactory;
  protected $fillable=[
    'facebook_page_id','page_membership_id','type','message','link','image_url','scheduled_at','status','fb_post_id','error'
  ];
  protected $casts=['scheduled_at'=>'datetime'];
  public function page(){ return $this->belongsTo(FacebookPage::class,'facebook_page_id'); }
  public function membership(){ return $this->belongsTo(PageMembership::class,'page_membership_id'); }
}