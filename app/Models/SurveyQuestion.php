<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type',
        'options',
        'required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    // الاستبيان التابع له هذا السؤال
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    // الإجابات المرتبطة (سننشئها لاحقًا)
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
