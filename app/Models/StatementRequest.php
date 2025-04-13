<?php

// app/Models/StatementRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementRequest extends Model
{
    use HasFactory;

    // تحديد اسم الجدول في قاعدة البيانات
    protected $table = 'statement_requests';

    // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
        'user_id', 'subtype', 'reason', 'status'
    ];

    /**
     * علاقة الطلب بالمستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);  // ربط الطلب بالمستخدم
    }

    /**
     * علاقة الطلب بالمرفقات
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');  // ربط الطلب بالمرفقات
    }

    /**
     * علاقة الطلب بالموافقات
     */
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');  // ربط الطلب بالموافقات
    }
}

