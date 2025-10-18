<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Post extends Model { protected $fillable=['user_id','page_id','fb_post_id','message','status','scheduled_at']; }
