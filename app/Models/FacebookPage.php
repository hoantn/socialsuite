<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FacebookPage extends Model {
    use HasFactory;
    protected $fillable=['page_id','name','picture_url','category'];
    public function memberships(){ return $this->hasMany(PageMembership::class); }
    public function posts(){ return $this->hasMany(Post::class,'facebook_page_id'); }
    public function getRouteKeyName(){ return 'id'; }
}
