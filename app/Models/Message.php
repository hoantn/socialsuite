<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $fillable = ['conversation_id','type','body','raw'];
    protected $casts = ['raw'=>'array'];
    public function conversation() { return $this->belongsTo(Conversation::class); }
}
