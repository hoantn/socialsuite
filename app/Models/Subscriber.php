<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class Subscriber extends Model{protected $fillable=['page_id','psid','name','avatar','opted_out'];}