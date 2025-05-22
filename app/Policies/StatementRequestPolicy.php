<?php

namespace App\Policies;

use App\Models\StatementRequest;
use App\Models\User;

class StatementRequestPolicy
{
    /**
     * عرض كل الطلبات
     */
    public function viewAny(User $user): bool
{
    // نسمح للجميع بالدخول، وسنفلتر داخل الـ Controller
    return $user->hasPermissionTo('view statement requests');
}

    public function view(User $user, StatementRequest $request): bool
    {
        return $user->id === $request->user_id || $user->hasRole('admin') || $user->hasRole('hr');
    }
 public function update(User $user, StatementRequest $request): bool
    {
        return $user->id === $request->user_id || $user->hasRole('admin') || $user->hasRole('hr');
    }
    /**
     * عرض طلب واحد
     */


    /**
     * إنشاء طلب جديد
     */
    public function create(User $user): bool
    {
        return $user->hasRole('employee');
    }
public function approve(User $user, StatementRequest $statementRequest): bool
{
    return $statementRequest->status === 'pending' &&
           ($user->hasRole('hr') || $user->hasRole('admin'));
}
public function reject(User $user, StatementRequest $statementRequest): bool
{
    return $statementRequest->status === 'pending' &&
           ($user->hasRole('hr') || $user->hasRole('admin'));
}



    /**
 * حذف طلب بيان
 */
public function delete(User $user, StatementRequest $statementRequest): bool
{
    // السماح بحذف الطلب إذا كان المستخدم هو صاحب الطلب أو لديه دور admin أو hr
    return $user->id === $statementRequest->user_id || $user->hasRole('admin') || $user->hasRole('hr');
}

}
