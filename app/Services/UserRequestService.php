<?php

namespace App\Services;

use App\Models\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserRequestService
{
    /**
     * عرض جميع الطلبات (حسب صلاحية المستخدم).
     */
    public function index(): Collection
    {
        $user = Auth::user();

        return $user->can('view requests')
            ? UserRequest::with('user')->latest()->get()
            : UserRequest::with('user')->where('user_id', $user->id)->latest()->get();
    }

    /**
     * عرض الطلبات المعلقة فقط.
     */
    public function pendingRequests(): Collection
    {
        return UserRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    /**
     * عرض الطلبات حسب نوع معين.
     */
    public function byType(string $type): Collection
    {
        return UserRequest::with('user')
            ->where('type', $type)
            ->latest()
            ->get();
    }

    /**
     * إنشاء طلب جديد.
     */
    public function store(Request $request): UserRequest
    {
        return UserRequest::create([
            'type' => $request->type,
            'subtype' => $request->subtype,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => $request->status ?? 'pending',
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * تعديل طلب موجود.
     */
    public function update(Request $request, UserRequest $userRequest): UserRequest
    {
        $userRequest->update([
            'type' => $request->type,
            'subtype' => $request->subtype,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => $request->status ?? 'pending',
        ]);

        return $userRequest;
    }

    /**
     * حذف طلب.
     */
    public function destroy(UserRequest $userRequest): void
    {
        $userRequest->delete();
    }

    /**
     * الموافقة على طلب.
     */
    public function approve(UserRequest $userRequest): UserRequest
    {
        $user = auth('sanctum')->user();

        // السماح فقط لـ HR أو Manager
        $role = $user->getRoles()->first();
        if (!in_array($role, ['hr', 'manager'])) {
            abort(403, 'يُسمح فقط لـ HR أو المدير بالموافقة على الطلبات.');
        }

        // لا تسمح بتكرار الموافقة من نفس الشخص
        if ($userRequest->approvals()->where('approved_by', $user->id)->exists()) {
            abort(400, 'لقد قمت بالموافقة مسبقًا على هذا الطلب.');
        }

        // تسجيل موافقة جديدة
        $userRequest->approvals()->create([
            'approved_by' => $user->id,
            'role'        => $role,
            'status'      => 'approved',
            'comment'     => 'تمت الموافقة من قبل ' . $user->name,
        ]);

        // التحقق: هل اكتملت الموافقة من HR و Manager؟
        $requiredRoles = ['hr', 'manager'];
        $approvedRoles = $userRequest->approvals()
            ->where('status', 'approved')
            ->pluck('role')
            ->unique()
            ->toArray();

        $allApproved = !array_diff($requiredRoles, $approvedRoles);

        // إذا تمت الموافقة من كل الأدوار المطلوبة، نحدث حالة الطلب إلى approved
        if ($allApproved) {
            $userRequest->status = 'approved';
            $userRequest->save();
        }

        return $userRequest;
    }

    /**
     * رفض طلب.
     */
    public function reject(UserRequest $userRequest): UserRequest
    {
        $user = auth('sanctum')->user();

        // السماح فقط لـ HR أو Manager
        $role = $user->getRoles()->first();
        if (!in_array($role, ['hr', 'manager'])) {
            abort(403, 'يُسمح فقط لـ HR أو المدير برفض الطلبات.');
        }

        // لا تسمح بتكرار الرفض أو الموافقة من نفس الشخص
        if ($userRequest->approvals()->where('approved_by', $user->id)->exists()) {
            abort(400, 'لقد قمت بالتصويت مسبقًا على هذا الطلب.');
        }

        // تسجيل الرفض
        $userRequest->approvals()->create([
            'approved_by' => $user->id,
            'role'        => $role,
            'status'      => 'rejected',
            'comment'     => 'تم الرفض من قبل ' . $user->name,
        ]);

        // بمجرد أن يرفض أحدهم، يتم رفض الطلب ككل
        $userRequest->status = 'rejected';
        $userRequest->save();

        return $userRequest;
    }



    /**
     * إلغاء الطلب (من طرف الموظف فقط).
     */
    public function cancel(UserRequest $userRequest): UserRequest
    {
        $user = auth('sanctum')->user();

        // التأكد من أن المستخدم هو صاحب الطلب
        if ($userRequest->user_id !== $user->id) {
            abort(403, 'لا يمكنك إلغاء طلب لا تملكه.');
        }

        // السماح بالإلغاء فقط إذا كان الطلب في حالة "قيد الانتظار"
        if ($userRequest->status !== 'pending') {
            abort(400, 'لا يمكن إلغاء هذا الطلب لأنه ليس قيد الانتظار.');
        }

        // التحقق من وجود أي موافقات عليه، وإذا وُجدت، نمنع الإلغاء
        if ($userRequest->approvals()->exists()) {
            abort(400, 'لا يمكن إلغاء هذا الطلب بعد أن بدأ مراجعته.');
        }

        // تنفيذ الإلغاء
        $userRequest->status = 'cancelled';
        $userRequest->save();

        return $userRequest;
    }



    /**
     * التحقق من إمكانية رفع إثبات.
     */
    public function canUploadProof(UserRequest $userRequest): bool
    {
        return $userRequest->type === 'leave' &&
               $userRequest->subtype !== 'administrative' &&
               $userRequest->user_id === Auth::id();
    }
}
