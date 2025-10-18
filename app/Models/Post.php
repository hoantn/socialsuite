<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'facebook_page_id','type','message','link','image_url',
        'scheduled_at','status','fb_post_id','error'
    ];
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
    public function page()
    {
        return $this->belongsTo(FacebookPage::class, 'facebook_page_id');
    }
}
