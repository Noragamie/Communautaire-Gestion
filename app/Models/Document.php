<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['profile_id','type','path','original_name','mime_type','size'];

    public function profile() { return $this->belongsTo(Profile::class); }

    public function getUrlAttribute() { return asset('storage/'.$this->path); }
}
