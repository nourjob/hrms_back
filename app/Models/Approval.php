<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'approvable_id',
        'approvable_type',
        'approved_by',
        'role',
        'status',
        'comment',
    ];
    public function approvable()
    {
        return $this->morphTo();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
