<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ScheduledPost extends Model {
  use HasFactory;
  protected $table = 'scheduled_posts';
  protected $fillable = ['page_id','page_name','message','media_type','media_paths','timezone','publish_at','status','tries','last_error','meta'];
  protected $casts = ['media_paths'=>'array','publish_at'=>'datetime','meta'=>'array'];
}
