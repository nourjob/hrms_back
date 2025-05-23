<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray($request)
    {
        $currentUser = auth()->user();

        return [
            'id' => $this->id,
            'subtype' => $this->subtype,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'status' => $this->status,

            // معلومات المستخدم
            'user_name' => $this->user?->name ?? 'المستخدم غير معروف',

            // قائمة الموافقات
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),

            // قائمة المرفقات
            'attachments' => $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'file_name' => basename($attachment->file_path),
                    'url' => asset('storage/' . $attachment->file_path),
                ];
            }),

            // معلومات المراجعة
'has_approved' => false,
'can_approve' => false,


            // تواريخ
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];
    }
}
