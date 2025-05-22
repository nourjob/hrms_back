<?php

namespace App\Policies;

use App\Models\CourseRequest;
use App\Models\User;

class CourseRequestPolicy
{
    /**
     * عرض كل الطلبات (HR يرى الكل - الموظف يرى طلباته فقط)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view course requests');
    }

    /**
     * عرض طلب محدد
     */
    public function view(User $user, CourseRequest $courseRequest): bool
    {
        return $user->hasRole('hr') ||$user->hasRole('admin') || $user->id === $courseRequest->user_id;
    }
      public function update(User $user, CourseRequest $courseRequest): bool
    {
        return $user->hasRole('hr') ||$user->hasRole('admin') || $user->id === $courseRequest->user_id;
    }

    /**
     * إنشاء طلب جديد
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create course request');
    }

    /**
     * الموافقة أو الرفض
     */
    public function approve(User $user, CourseRequest $courseRequest): bool
    {
        return $user->hasPermissionTo('approve course request') && $courseRequest->status === 'pending';
    }
}
