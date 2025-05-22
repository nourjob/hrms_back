<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,        // approved أو rejected
            'role' => $this->role,            // hr أو manager
            'comment' => $this->comment,      // تعليق المعتمد

            // فقط الاسم وليس كل بيانات المستخدم
            'approved_by' => $this->approver?->name ?? 'غير معروف',

            // التاريخ بصيغة كاملة
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
