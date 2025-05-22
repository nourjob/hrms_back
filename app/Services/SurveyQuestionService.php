<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Auth;

class SurveyQuestionService
{
   
    public function createQuestion(Survey $survey, array $data): SurveyQuestion
    {
        $data['survey_id'] = $survey->id;
        return SurveyQuestion::create($data);
    }

    
    public function updateQuestion(SurveyQuestion $question, array $data): SurveyQuestion
    {
        $question->update($data);
        return $question;
    }

   
    public function deleteQuestion(SurveyQuestion $question): bool
    {
        return $question->delete();
    }
}