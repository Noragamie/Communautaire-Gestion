<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModificationRequest extends Model
{
    protected $fillable = ['profile_id', 'status', 'data', 'new_photo', 'motif_rejet'];

    protected $casts = ['data' => 'array'];

    public function profile()   { return $this->belongsTo(Profile::class); }
    public function documents() { return $this->hasMany(ModificationRequestDocument::class); }

    public function isPending()  { return $this->status === 'pending'; }
    public function isApproved() { return $this->status === 'approved'; }
    public function isRejected() { return $this->status === 'rejected'; }
}
