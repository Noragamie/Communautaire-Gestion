<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    protected $fillable = ['email','token','subscribed','user_id'];
    protected $casts    = ['subscribed'=>'boolean'];

    protected static function booted()
    {
        static::creating(fn($n) => $n->token = Str::random(40));
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
