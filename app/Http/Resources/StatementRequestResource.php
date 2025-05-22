<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->subtype,
            'reason' => $this->reason,
            'status' => $this->status,
            'comment' => $this->comment,
            'user_name' => $this->user ? $this->user->name : 'المستخدم غير معروف',
            'attachments' => $this->attachments->map(function ($attachment) {
                return asset('storage/' . $attachment->file_path);
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
