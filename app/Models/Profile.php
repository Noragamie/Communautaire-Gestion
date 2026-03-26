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
        'site_web','niveau_etude','status','motif_rejet','contact_visible',
    ];

    protected $casts = ['contact_visible' => 'boolean'];

    // Relations
    public function user()                { return $this->belongsTo(User::class); }
    public function category()            { return $this->belongsTo(Category::class); }
    public function documents()           { return $this->hasMany(Document::class); }
    public function modificationRequest() { return $this->hasOne(ModificationRequest::class)->latestOfMany(); }

    // Scopes
    public function scopeApproved(Builder $q)  {
        return $q->where('status','approved')
                 ->whereHas('user', fn($u) => $u->where('is_suspended', false)
                                                 ->where('is_active', true));
    }
    public function scopePending(Builder $q)   { return $q->where('status','pending'); }
    public function scopeRejected(Builder $q)  { return $q->where('status','rejected'); }
    public function scopeSuspended(Builder $q) { return $q->where('status','suspended'); }

    // Helpers status
    public function isPending()   { return $this->status === 'pending'; }
    public function isApproved()  { return $this->status === 'approved'; }
    public function isRejected()  { return $this->status === 'rejected'; }
    public function isSuspended() { return $this->status === 'suspended'; }
}
