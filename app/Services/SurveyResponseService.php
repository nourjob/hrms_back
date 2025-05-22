<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyResponse;

class SurveyResponseService
{
    /**
     * إنشاء استجابة للاستبيان
     */
    public function createSurveyResponse(Survey $survey, array $data): SurveyResponse
    {
        $data['user_id'] = auth()->id();
        return $survey->responses()->create($data);
    }
}