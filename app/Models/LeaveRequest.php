<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    // تحديد اسم الجدول في قاعدة البيانات
    protected $table = 'leave_requests';

    // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
        'user_id', 'subtype', 'start_date', 'end_date', 'reason', 'status'
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
