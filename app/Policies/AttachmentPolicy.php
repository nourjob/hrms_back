<?php
// app/Policies/AttachmentPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Attachment;

class AttachmentPolicy
{
    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف المرفق.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        // فقط المستخدم الذي رفع المرفق أو الـ admin يمكنه حذفه
        return $user->id === $attachment->uploaded_by || $user->hasRole('admin');
    }
}
