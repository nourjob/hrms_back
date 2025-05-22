<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * يسمح فقط لـ Admin و HR و الموظف نفسه بتعديل بياناته الشخصية
     */
    public function updateProfile(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') || $user->id === $model->id;
    }

    /**
     * يسمح للمستخدم الـ admin و الـ hr و الـ manager برؤية المستخدمين.
     * الماناجر يستطيع رؤية المستخدمين من نفس القسم فقط.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') || $user->hasRole('manager');
    }

    /**
     * السماح فقط للـ admin، الموظف نفسه، أو الماناجر بنفس القسم.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin')
            || $user->id === $model->id
            || ($user->hasRole('manager') && $user->department_id === $model->department_id);
    }

    /**
     * السماح للـ admin بإنشاء أي مستخدم، والـ hr بإنشاء مستخدم بشرط ألا يكون دوره admin.
     */
     /**
     * السماح بإنشاء مستخدم جديد.
     * admin كامل الصلاحية.
     * hr يمكنه إنشاء فقط مستخدمين أقل منه (manager, employee).
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin') || $authUser->hasRole('hr');
    }

    /**
     * السماح بالتعديل على المستخدم.
     * admin كامل الصلاحية.
     * hr لا يعدل admin أو hr.
     * manager يعدل موظفين قسمه فقط.
     * الموظف يعدل بياناته فقط.
     */
    public function update(User $authUser, User $user): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        if ($authUser->id === $user->id) {
            return true;
        }

        if ($authUser->hasRole('hr')) {
            return ! $user->hasRole('admin') && ! $user->hasRole('hr');
        }

        if ($authUser->hasRole('manager')) {
            return $authUser->department_id === $user->department_id
                && ! $user->hasRole('admin')
                && ! $user->hasRole('hr')
                && ! $user->hasRole('manager');
        }

        return false;
    }

    /**
     * السماح بحذف المستخدم.
     * admin كامل الصلاحية.
     * hr يحذف فقط المستخدمين الأقل منه.
     * manager يحذف موظفي قسمه فقط ومن أدوار أقل.
     */
    public function delete(User $authUser, User $user): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        if ($authUser->hasRole('hr')) {
            return ! $user->hasRole('admin') && ! $user->hasRole('hr');
        }

        if ($authUser->hasRole('manager')) {
            return $authUser->department_id === $user->department_id
                && ! $user->hasRole('admin')
                && ! $user->hasRole('hr')
                && ! $user->hasRole('manager');
        }

        return false;
    }
} 