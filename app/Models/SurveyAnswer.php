<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_response_id',
        'survey_question_id',
        'answer',
    ];

    protected $casts = [
        'answer' => 'array',
    ];

    // الإجابة الكاملة التي تنتمي لها هذه الإجابة الجزئية
    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    // السؤال المرتبط
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
    public function attachments()
{
    return $this->morphMany(Attachment::class, 'attachable');
}

}
