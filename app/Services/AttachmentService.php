<?php
// app/Services/AttachmentService.php


// app/Services/AttachmentService.php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
class AttachmentService
{
    /**
     * إنشاء مرفق جديد.
     *
     * @param  array  $data
     * @param  mixed  $attachableModel  // إرفاقه بالنموذج (مثل leave_requests أو statement_requests)
     * @return \App\Models\Attachment
     */
    public function createAttachment(array $data, $attachableModel)
    {
        // تحقق من وجود ملف مرفق
        if (isset($data['proof_file'])) {
            // تخزين الملف في التخزين العام
            $filePath = Storage::put('proofs', $data['proof_file']);
            $data['file_path'] = $filePath;  // مسار الملف
            $data['file_type'] = $data['proof_file']->getClientOriginalExtension();  // نوع الملف
            $data['uploaded_by'] = auth()->id();  // المستخدم الذي قام برفع المرفق
            $data['attachable_type'] = get_class($attachableModel);  // نوع الموديل المرتبط (LeaveRequest أو StatementRequest)
            $data['attachable_id'] = $attachableModel->id;  // ID الخاص بالطلب الذي يتم ربط المرفق به

            // إنشاء المرفق
            return Attachment::create($data);
        }

        return null;  // في حال عدم وجود مرفق
    }

    /**
     * حذف مرفق.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return bool|null
     */
    public function deleteAttachment(Attachment $attachment)
    {
        // حذف المرفق من التخزين
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }

        return $attachment->delete();  // حذف المرفق من قاعدة البيانات
    }
}


