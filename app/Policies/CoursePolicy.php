<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{   
    /**
     * عرض كل الدورات
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view courses');
    }

    /**
     * عرض دورة واحدة
     */
    public function view(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('view courses')
                || $user->hasRole('hr') ||$user->hasRole('admin') || $user->id === $course->user_id;  ;
    }

    /**
     * إنشاء دورة جديدة
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create course');
    }

    /**
     * تعديل دورة
     */
    public function update(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('update course');
    }

    /**
     * حذف دورة
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->hasPermissionTo('delete course');
    }
}
