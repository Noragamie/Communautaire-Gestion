<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModificationRequestDocument extends Model
{
    protected $fillable = ['modification_request_id', 'type', 'path', 'file_data', 'original_name', 'mime_type', 'size'];

    public function modificationRequest() { return $this->belongsTo(ModificationRequest::class); }
}
