<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class SurveyQuestionPolicy
{
    
    public function view(User $user, Survey $survey): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') 
        || $user->hasRole('manager')
        || $user->hasRole('employee');;
    }

    
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr')
            || $user->hasRole('manager')
            || $user->hasRole('employee');

    }

   
    public function create(User $user, Survey $survey): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr');
    }

    
    public function update(User $user, Survey $survey, SurveyQuestion $question): bool
    {
        $isBeforeStart = now()->lt($survey->start_date); // التأكد أن الوقت الحالي قبل start_date
        return ($user->hasRole('admin') || $user->hasRole('hr')) && $isBeforeStart;
    }


    
    public function delete(User $user, SurveyQuestion $question): bool
    {
        return$user->hasRole('admin') || $user->hasRole('hr');
    }

}
