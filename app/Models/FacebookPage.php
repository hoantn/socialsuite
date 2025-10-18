<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FacebookPage extends Model
{
    use HasFactory;
    protected $fillable = ['fb_user_id','page_id','name','access_token'];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
