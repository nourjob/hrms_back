<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class LeaveRequestService
{
    /**
     * إنشاء طلب إجازة جديد مع مرفق اختياري
     */
    public function create(array $data, $file = null): LeaveRequest
    {
        $data['status'] = 'pending';
        $data['user_id'] = Auth::id();

        $leaveRequest = LeaveRequest::create($data);

        if ($file) {
            $path = $file->store('attachments/leave_proofs', 'public');

            $leaveRequest->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        return $leaveRequest->load([
            'user',
            'attachments',
            'approvals.approver',
        ]);
    }

    /**
     * جلب طلب معين مع علاقاته
     */
    public function getById(int $id): ?LeaveRequest
    {
        return LeaveRequest::with(['user', 'approvals.approver', 'attachments'])->find($id);
    }

    /**
     * جلب كل الطلبات أو للمستخدم فقط
     */
public function getAll()
{
    $user = auth()->user();

    $query = LeaveRequest::with(['user', 'approvals.approver', 'attachments']);

    if ($user->hasRole('admin')) {
        return $query->get();
    }

    if ($user->hasRole('hr')) {
        return $query->whereHas('approvals', function ($q) {
            $q->where('role', 'manager')->where('status', 'approved');
        })->get();
    }

    if ($user->hasRole('manager')) {
        return $query->whereHas('user', function ($q) use ($user) {
            $q->where('department_id', $user->department_id);
        })->get();
    }

    return $query->where('user_id', $user->id)->get();
}



    /**
     * تعديل طلب إجازة
     */
public function update(LeaveRequest $leaveRequest, array $data, $file = null): LeaveRequest
{
    DB::transaction(function () use ($leaveRequest, $data, $file) {
        // تحديث البيانات الأساسية
        $leaveRequest->update($data);

        // إذا كان هناك مرفق جديد
        if ($file && $data['subtype'] !== 'administrative') {
            // حذف المرفق القديم فقط إذا كان موجودًا
            $leaveRequest->attachments()->delete();

            // تخزين المرفق الجديد
            $path = $file->store('attachments/leave_proofs', 'public');

            // حفظ المرفق الجديد
            $leaveRequest->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => Auth::id(), // يجب التأكد من استخدام auth()->id() أو Auth::id()
            ]);
        }
    });

    // إرجاع البيانات بعد التحديث
    return $leaveRequest->fresh()->load([
        'attachments',
        'approvals.approver',
    ]);
}






    
    public function delete(LeaveRequest $leaveRequest): bool
    {
        return $leaveRequest->delete();
    }
public function approve(LeaveRequest $leaveRequest, string $status, ?string $comment = null): LeaveRequest
{
    if (!in_array($status, ['approved', 'rejected'])) {
        throw new \InvalidArgumentException('Invalid approval status');
    }

    DB::transaction(function () use ($leaveRequest, $status, $comment) {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('لم يتم العثور على المستخدم.');
        }

        // ✅ السماح للـ admin بالموافقة أو الرفض مباشرة
        if ($user->hasRole('admin')) {
            $leaveRequest->approvals()->delete(); // حذف الموافقات السابقة إن وجدت
            $leaveRequest->approvals()->create([
                'approved_by' => $user->id,
                'role' => 'admin',
                'status' => $status,
                'comment' => $comment,
            ]);

            $leaveRequest->update(['status' => $status]); // مباشرةً تغيير الحالة إلى approved أو rejected
            return;
        }

        // ✅ موافقة أو رفض من HR أو Manager
        if ($user->hasRole('hr') || $user->hasRole('manager')) {
            $role = $user->hasRole('hr') ? 'hr' : 'manager';

            $leaveRequest->approvals()->create([
                'approved_by' => $user->id,
                'role' => $role,
                'status' => $status,
                'comment' => $comment,
            ]);

            // في حال الرفض
            if ($status === 'rejected') {
                $leaveRequest->update(['status' => 'rejected']);
            } elseif ($this->isFullyApproved($leaveRequest)) {
                // إذا كانت الموافقة مكتملة، اجعل الحالة "approved"
                $leaveRequest->update(['status' => 'approved']);
            }

            return;
        }

        throw new \Exception('ليس لديك صلاحية الموافقة على هذا الطلب.');
    });

    return $leaveRequest->fresh()->load([
        'user',
        'attachments',
        'approvals.approver',
    ]);
}
 
    /**
     * التحقق من اكتمال جميع الموافقات المطلوبة
     */
    protected function isFullyApproved(LeaveRequest $leaveRequest): bool
    {
        $requiredRoles = ['hr', 'manager'];

        $approvedRoles = $leaveRequest->approvals()
            ->where('status', 'approved')
            ->pluck('role')
            ->unique()
            ->toArray();

        return empty(array_diff($requiredRoles, $approvedRoles));
    }
}
