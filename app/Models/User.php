<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;  // استخدام HasRoles من Spatie

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // استبدال HasRolesAndAbilities بـ HasRoles

    /**
     * الحقول القابلة للملء.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'job_number',
        'status',
        'manager_id',
        'department_id',
        'marital_status',
        'number_of_children',
        'qualification',
        'phone',
        'address',
        'university',
        'graduation_year',
         'survey_completed',
          'salary_details',
           'status_details',
    ];

    /**
     * الحقول المخفية من JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويلات القيم.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ علاقات النظام

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function manager()
    {
        // علاقة `manager` تشير إلى `User` آخر في نفس الجدول
        return $this->belongsTo(User::class, 'manager_id');  // استخدام `manager_id` للربط
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'user_id');  // ربط الطلبات بالإجازة
    }

    /**
     * علاقة المستخدم بطلبات البيان (بيان الوضع وبيان الراتب).
     */
    public function statementRequests()
    {
        return $this->hasMany(StatementRequest::class, 'user_id');  // ربط الطلبات بالبيان المالي
    }

    public function courseRequests()
    {
        return $this->hasMany(CourseRequest::class);
    }


    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * إرسال إشعار إعادة تعيين كلمة المرور.
     */
    public function sendPasswordResetNotification($token)
    {
        //$this->notify(new ResetPassword($token));
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }


}
