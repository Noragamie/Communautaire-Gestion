<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','category_id','bio','competences','experience',
        'localisation','secteur_activite','photo','telephone',
        'site_web','niveau_etude','status','motif_rejet',
    ];

    // Relations
    public function user()      { return $this->belongsTo(User::class); }
    public function category()  { return $this->belongsTo(Category::class); }
    public function documents() { return $this->hasMany(Document::class); }

    // Scopes
    public function scopeApproved(Builder $q)  { return $q->where('status','approved'); }
    public function scopePending(Builder $q)   { return $q->where('status','pending'); }
    public function scopeRejected(Builder $q)  { return $q->where('status','rejected'); }
    public function scopeSuspended(Builder $q) { return $q->where('status','suspended'); }

    // Helpers status
    public function isPending()   { return $this->status === 'pending'; }
    public function isApproved()  { return $this->status === 'approved'; }
    public function isRejected()  { return $this->status === 'rejected'; }
    public function isSuspended() { return $this->status === 'suspended'; }
}
