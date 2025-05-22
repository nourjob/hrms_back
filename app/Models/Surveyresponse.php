<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'user_id',
        'submitted_at',
    ];

    protected $dates = ['submitted_at'];

    // الاستبيان المرتبط
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    // الموظف الذي أجاب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الإجابات المفصلة لكل سؤال
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class)->with('question');
    }
}
