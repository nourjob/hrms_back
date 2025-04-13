<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $with = ['user', 'attachments', 'approvals'];

     // تحديد اسم الجدول في قاعدة البيانات
     protected $table = 'requests';
    // إضافة الأعمدة التي يمكن تعيينها بشكل جماعي (Mass Assignment)
    protected $fillable = [
        'user_id',  // يجب إضافة هذا العمود للسماح بملئه
        'type',
        'subtype',
        'start_date',
        'end_date',
        'reason',
        'status',
          'salary_details', 'status_details'
    ];

    /**
     * علاقة الطلب بالمستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الطلب بالموافقات
     */
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * علاقة الطلب بالمرفقات
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
