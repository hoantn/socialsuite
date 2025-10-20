<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model {
    protected $fillable = ['page_id','sender_id','recipient_id','meta'];
    protected $casts = ['meta'=>'array'];
    public function messages() { return $this->hasMany(Message::class); }
}
