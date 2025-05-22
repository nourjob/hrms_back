<?php

namespace App\Services;

use App\Models\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseRequestService
{
    /**
     * إنشاء طلب دورة جديد
     */
    public function create(array $data): CourseRequest
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // تحديد الحالة كـ pending في البداية

        return CourseRequest::create($data);
    }

    /**
     * الموافقة على الطلب مع مرفق أو رابط اختياري
     */
public function approve(CourseRequest $request, $file = null, ?string $link = null, ?string $comment = null): CourseRequest
{
    DB::transaction(function () use ($request, $file, $link, $comment) {
        // تخزين المرفق في حال وجوده
        if ($file) {
            $path = $file->store('attachments/courses', 'public');
            $request->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        // تحديث حالة الطلب إلى "approved" وتسجيل الرابط إذا وُجد
        $request->update([
            'status' => 'approved',
            'comment' => $comment,
            'link' => $link, // ✅ تم الإضافة هنا
        ]);
    });

    return $request->fresh()->load(['attachments']);
}


    /**
     * رفض الطلب من قبل HR
     */
    public function reject(CourseRequest $request, string $comment): CourseRequest
    {
        $request->update([
            'status' => 'rejected',
            'comment' => $comment,
        ]);

        return $request->fresh();
    }

    /**
     * جلب جميع الطلبات (HR يشوف الكل - الموظف يشوف طلباته فقط)
     */
 public function getAll(?int $userId = null)
{
    $user = auth()->user();

    $query = CourseRequest::with(['attachments', 'user', 'course']);

    if ($user->hasRole('admin') || $user->hasRole('hr')) {
        return $query->get();
    }

    if ($user->hasRole('manager')) {
        return $query->whereHas('user', fn($q) =>
            $q->where('department_id', $user->department_id)
        )->get();
    }

    // employee
    return $query->where('user_id', $user->id)->get();
}


    /**
     * حذف طلب الدورة
     */
    public function delete(CourseRequest $courseRequest): bool
    {
        if ($courseRequest->status !== 'pending') {
            throw new \Exception('لا يمكن حذف الطلب إلا إذا كانت حالته قيد المراجعة (pending)');
        }

        return $courseRequest->delete();
    }
}
