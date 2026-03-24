<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actuality extends Model
{
    protected $fillable = ['user_id','title','content','image','is_published','published_at'];
    protected $casts    = ['is_published'=>'boolean','published_at'=>'datetime'];

    public function author() { return $this->belongsTo(User::class,'user_id'); }

    public function scopePublished($q) { return $q->where('is_published',true)->latest('published_at'); }
}
