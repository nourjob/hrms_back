<?php
// app/Policies/LeaveRequestPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
public function viewAny(User $user): bool
{
    // admin و hr يمكنهم رؤية كل الطلبات
    if ($user->hasRole('admin') || $user->hasRole('hr')) {
        return true;
    }

    // المدير يرى فقط طلبات قسمه
    if ($user->hasRole('manager')) {
        return true; // يسمح له برؤية لكن الاستعلام في السيرفيس يفلتر حسب القسم
    }

    // الموظف العادي يرى فقط طلباته (يمكن التحكم بذلك في الاستعلام)
    if ($user->hasRole('employee')) {
        return true;
    }

    return false; // الباقي ممنوع
}


public function view(User $user, LeaveRequest $leaveRequest): bool
{
    return $user->hasRole('admin') ||$user->hasRole('hr')
        || $user->id === $leaveRequest->user_id 
        || ($user->hasRole('manager') && $user->department_id === $leaveRequest->user->department_id);
}


    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('admin') || ($user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending');
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('admin') || ($user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending');
    }

public function approve(User $user, LeaveRequest $leaveRequest): bool
{
    if ($user->hasRole('admin')) {
        return true;
    }

    if ($user->hasRole('manager')) {
        return $leaveRequest->status === 'pending' &&
               $user->department_id === $leaveRequest->user->department_id;
    }

    if ($user->hasRole('hr')) {
        return $leaveRequest->status === 'pending';
    }

    return false;
}


    public function create(User $user): bool
    {
        return $user->hasRole('employee') && $user->can('request leave') && $user->can('upload leave proof');
    }
}
