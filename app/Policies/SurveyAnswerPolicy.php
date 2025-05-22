<?php

namespace App\Policies;

use App\Models\SurveyAnswer;
use App\Models\User;

class SurveyAnswerPolicy
{
    /**
     * عرض إجابة معينة
     */
    public function view(User $user, SurveyAnswer $answer): bool
    {
        // يمكن للمستخدم نفسه أو HR أو Admin عرض الإجابة
        return $user->id === $answer->response->user_id || $user->hasRole('hr') || $user->hasRole('admin');
    }

    /**
     * إنشاء إجابة جديدة
     */
    public function create(User $user, SurveyAnswer $answer): bool
    {
        return $user->id !== null; // أي مستخدم مسجل يمكنه تقديم إجابة
    }
}
