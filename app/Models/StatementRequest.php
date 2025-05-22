<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementRequest extends Model
{
    use HasFactory;

    protected $table = 'statement_requests';

    protected $fillable = [
        'user_id',
        'subtype',   // salary أو status
        'reason',    // الغرض من الطلب
        'status',    // pending, approved, rejected
        'comment',   // ملاحظات HR
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
