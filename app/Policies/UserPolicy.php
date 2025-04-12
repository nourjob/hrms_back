<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض أي من النماذج.
     */
    public function viewAny(User $user): bool
    {
        // مدير الموارد (hr) يمكنه رؤية جميع المستخدمين
        // الماناجر يمكنه فقط رؤية المستخدمين الذين ينتمون إلى قسمه
        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            return true; // يسمح لجميع مستخدمي الـ admin و hr
        }

        if ($user->hasRole('manager')) {
            // الماناجر يستطيع رؤية المستخدمين من نفس القسم فقط
            return $user->department_id == request()->route('department_id');
        }

        return false; // إذا لم يكن لديه صلاحية
    }
    public function assignRole(User $user): bool
{
    // فقط admin يمكنه تعيين الأدوار
    return $user->hasRole('admin');
}


    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض النموذج.
     */
    public function view(User $user, User $model): bool
    {
        // يمكن للمستخدم admin أو نفسه رؤية الحساب
        return $user->hasRole('admin') || $user->id === $model->id || $user->hasRole('manager') && $user->department_id === $model->department_id;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه إنشاء النماذج.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin'); // فقط الـ admin يمكنه إنشاء المستخدمين
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه تحديث النموذج.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف النموذج.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin') ;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه استعادة النموذج.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف النموذج بشكل دائم.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }
}
