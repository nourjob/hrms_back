<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description', 
        'type',
        'url',
        'target_department_id',
        'target_roles',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['survey_response_id'];
    protected $with = ['department', 'creator', 'questions']; // لو حاب تجهز اللودنج تلقائيًا

    public function getSurveyResponseIdAttribute()
    {
        if (!auth()->check()) return null;

        return $this->responses()
            ->where('user_id', auth()->id())
            ->value('id');
    }

    // القسم المستهدف
    public function department()
    {
        return $this->belongsTo(Department::class, 'target_department_id');
    }

    // منشئ الاستبيان
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // الأسئلة المرتبطة
    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    // الاستجابات المرتبطة
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
    public function attachments()
{
    return $this->morphMany(Attachment::class, 'attachable');
}

}
