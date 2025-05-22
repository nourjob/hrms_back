<?php

namespace App\Services;

use App\Models\StatementRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatementRequestService
{
    /**
     * إنشاء طلب بيان من قبل الموظف
     */
    public function create(array $data): StatementRequest
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        return StatementRequest::create($data);
    }

    /**
     * الموافقة على الطلب من قبل HR مع إرفاق البيان PDF
     */
    public function approve(StatementRequest $request, $file, ?string $comment = null): StatementRequest
    {
        DB::transaction(function () use ($request, $file, $comment) {
            // رفع الملف وتخزينه
            $path = $file->store('attachments/statements', 'public');

            // حفظ كمرفق
            $request->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => Auth::id(),
            ]);

            // تحديث حالة الطلب
            $request->update([
                'status' => 'approved',
                'comment' => $comment,
            ]);
        });

        return $request->fresh()->load('attachments');
    }

    /**
     * رفض الطلب من قبل HR
     */
   public function reject(StatementRequest $request, string $comment): StatementRequest
{
    $request->update([
        'status' => 'rejected',
        'comment' => $comment,
    ]);

    return $request->fresh();
}

    /**
     * جلب جميع الطلبات
     */
 public function getAll(?int $userId = null)
{
    $user = auth()->user();

    $query = StatementRequest::with(['attachments', 'user']);

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
     * جلب طلب واحد بالتفصيل
     */
    public function getById(int $id): ?StatementRequest
    {
        return StatementRequest::with('attachments')->find($id);
    }
}
