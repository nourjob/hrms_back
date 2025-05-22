<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Carbon;

class SurveyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr')|| $user->hasRole('employee') || $user->hasRole('manager');
    }
 
    
    public function view(User $user, Survey $survey): bool
{
    // ✅ admin و hr يمكنهم مشاهدة كل الاستبيانات
    if ($user->hasRole('admin') || $user->hasRole('hr')) {
        return true;
    }

    // ✅ فقط الاستبيانات المفعّلة
    if (!$survey->is_active) {
        return false;
    }

    // ✅ لا يُعرض إذا لم يبدأ بعد
    if ($survey->start_date && now()->lt(Carbon::parse($survey->start_date)->startOfDay())) {
    return false;
    }

    // ✅ لا يُعرض إذا انتهى تاريخه
    if ($survey->end_date && now()->greaterThan($survey->end_date)) {
        return false;
    }

    // ✅ تحقق من تطابق القسم المستهدف
    if ($survey->target_department_id && $survey->target_department_id !== $user->department_id) {
        return false;
    }

    // ✅ تحقق من تطابق الدور المستهدف
    if ($survey->target_roles && !array_intersect($survey->target_roles, $user->roles->pluck('name')->toArray())) {
        return false;
    }

    return true;
}






    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr');
    }

   

    public function update(User $user, Survey $survey): bool
{
    // المسؤول أو الموارد البشرية يمكنهم التعديل دائمًا
    if ($user->hasRole('admin') || $user->hasRole('hr')) {
        return true;
    }

    return false;
}



    public function delete(User $user, Survey $survey): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr');
    }
}
