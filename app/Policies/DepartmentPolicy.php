<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Department;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * تحديد ما إذا كان المستخدم يمكنه إنشاء قسم.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr'); 
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه تحديث القسم.
     */
    public function update(User $user, Department $department): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') || ($user->hasRole('manager') || $user->hasRole('hr') && $user->id === $department->manager_id);
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف القسم.
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr');
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض القسم.
     */
    public function view(User $user, Department $department): bool
{
    return $user->hasRole('admin') || $user->hasRole('hr') || ($user->hasRole('manager') && $user->id === $department->manager_id);
}

}
