<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'commune_id', 'title', 'content', 'image', 'is_published', 'published_at'];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->latest('published_at');
    }

    public function scopeForCommune($q, ?int $communeId)
    {
        if ($communeId === null) {
            return $q->whereRaw('1 = 0');
        }

        return $q->where('commune_id', $communeId);
    }
}
