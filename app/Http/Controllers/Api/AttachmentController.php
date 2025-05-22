<?php

namespace App\Http\Controllers\Api;

use App\Models\LeaveRequest;
use App\Models\StatementRequest;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;  // ربط الخدمة
    }

    /**
     * إضافة مرفق للطلب.
     */
    public function store(Request $request, $requestId, $type)
    {
        $this->authorize('create', Attachment::class);  // التحقق من صلاحيات المستخدم

        // التحقق من البيانات المدخلة
        $data = $request->validate([
            'proof_file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240',  // السماح بإرفاق الملفات
        ]);

        // التحقق من نوع الطلب (إجازة أو بيان) واختيار الموديل المناسب
        if ($type === 'leave') {
            $attachableModel = LeaveRequest::findOrFail($requestId); // إذا كان طلب إجازة
        } elseif ($type === 'statement') {
            $attachableModel = StatementRequest::findOrFail($requestId); // إذا كان طلب بيان
        } else {
            return response()->json(['message' => 'Invalid request type'], 400);
        }

        // إضافة المرفق باستخدام الخدمة
        $attachment = $this->attachmentService->createAttachment($data, $attachableModel);

        return response()->json([
            'message' => 'Attachment added successfully',
            'attachment' => $attachment,
            'file_url' => asset('storage/' . $attachment->file_path)  // إضافة رابط الملف
        ], 201);
    }

    /**
     * حذف المرفق.
     */
    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);  // التحقق من صلاحيات المستخدم

        $this->attachmentService->deleteAttachment($attachment);  // حذف المرفق باستخدام الخدمة

        return response()->json(['message' => 'Attachment deleted successfully'], 204);
    }
}
