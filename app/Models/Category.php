<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name','slug','description'];

    protected static function booted()
    {
        static::creating(fn($c) => $c->slug = Str::slug($c->name));
        static::updating(fn($c) => $c->slug = Str::slug($c->name));
    }

    public function profiles() { return $this->hasMany(Profile::class); }
}
