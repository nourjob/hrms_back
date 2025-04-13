<?php
// app/Policies/RequestPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRequest;

class RequestPolicy
{
    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض أي من النماذج.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr');
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض النموذج.
     */
    public function view(User $user, Request $request): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') || $user->id === $request->user_id;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه تعديل النموذج.
     */
    public function update(User $user, Request $request): bool
    {
        return $user->hasRole('admin') || $user->hasRole('hr') || $user->id === $request->user_id;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف النموذج.
     */
    public function delete(User $user, Request $request): bool
    {
        return $user->hasRole('admin');
    }
}
