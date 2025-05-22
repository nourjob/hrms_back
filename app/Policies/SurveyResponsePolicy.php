<?php

namespace App\Policies;

use App\Models\SurveyResponse;
use App\Models\User;

class SurveyResponsePolicy
{
    /**
     * عرض استجابة الاستبيان
     */
    public function view(User $user, SurveyResponse $response): bool
    {
        // يمكن للمستخدم نفسه أو HR أو Admin عرض استجابة
        return $user->id === $response->user_id || $user->hasRole('hr') || $user->hasRole('admin');
    }

    /**
     * إنشاء استجابة جديدة
     */
    public function create(User $user, SurveyResponse $response): bool
    {
        return $user->id !== null; // أي مستخدم مسجل يمكنه تقديم استجابة
    }
}
