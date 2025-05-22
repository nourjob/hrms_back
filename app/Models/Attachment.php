<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'file_path',
        'file_type',
        'uploaded_by',
        'attachable_id',     // ✅ أضف هذا
        'attachable_type',   // ✅ وأيضًا هذا
    ];
    const UPDATED_AT = null;
    public function attachable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
