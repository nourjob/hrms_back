<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class SurveyService
{ 
    
    public function createSurvey(array $data): Survey
    {
        $data['created_by'] = Auth::id();

        $survey = Survey::create($data);

        if ($survey->is_active) {
            $this->sendSurveyNotification($survey);
        }

        return $survey->fresh();
    }

    
    public function updateSurvey(Survey $survey, array $data): Survey
    {
        $wasInactive = !$survey->is_active;

        $survey->update($data);

        if ($wasInactive && $survey->is_active) {
            $this->sendSurveyNotification($survey);
        }

        return $survey->fresh();
    }

    
    protected function sendSurveyNotification(Survey $survey): void
    {
        $query = User::query();

        if ($survey->target_department_id) {
            $query->where('department_id', $survey->target_department_id);
        }

        if (!empty($survey->target_roles)) {
            $query->whereHas('roles', function ($q) use ($survey) {
                $q->whereIn('name', $survey->target_roles);
            });
        }

        $users = $query->get();

        NotificationService::sendToUsers($users, [
            'title' => 'استبيان جديد',
            'body' => 'تم نشر استبيان جديد بعنوان: ' . $survey->title,
            'type' => 'survey',
            'survey_id' => $survey->id,
        ]);
    }
}
