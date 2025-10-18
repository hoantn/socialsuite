<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/** [SOCIALSUITE][GPT][2025-10-18 09:40 +07] Model FacebookPage */
class FacebookPage extends Model {
    use HasFactory;
    protected $fillable = ['user_id','fb_user_id','page_id','name','access_token'];
}
