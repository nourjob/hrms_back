<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            // معلومات الدورة
            'course' => new CourseResource($this->whenLoaded('course')),
            'course_name' => $this->course ? $this->course->name : null,  // اسم الدورة مباشرة

            'custom_course_title' => $this->custom_course_title,
            'custom_course_provider' => $this->custom_course_provider,

            // معلومات عامة
            'reason' => $this->reason,
            'status' => $this->status,
            'comment' => $this->comment,
            'link' => $this->link, // ✅ تمت الإضافة هنا


            // المرفقات
            'attachments' => $this->attachments->map(function ($attachment) {
                return asset('storage/' . $attachment->file_path);
            }),

            // الموظف
            'user_name' => $this->user ? $this->user->name : 'المستخدم غير معروف',

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
