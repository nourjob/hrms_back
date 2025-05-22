<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseRequestResource;
use App\Models\CourseRequest;
use App\Services\CourseRequestService;
use Illuminate\Http\Request;

class CourseRequestController extends Controller
{
    // حقن خدمة CourseRequestService في الـ Controller
    protected $courseRequestService;

    public function __construct(CourseRequestService $courseRequestService)
    {
        $this->courseRequestService = $courseRequestService;
    }

    /**
     * عرض جميع طلبات الدورات
     */
    public function index()
    {
        $this->authorize('viewAny', CourseRequest::class);

        // جلب جميع الطلبات بناءً على دور المستخدم
        $requests = $this->courseRequestService->getAll(
            auth()->user()->hasRole('hr') ? null : auth()->id()
        );

        return CourseRequestResource::collection(
            $requests->load(['user', 'course', 'attachments'])
        );
    }

    /**
     * عرض طلب دورة واحد بالتفصيل
     */
    public function show(CourseRequest $courseRequest)
    {
        $this->authorize('view', $courseRequest);

        return new CourseRequestResource(
            $courseRequest->load(['user', 'course', 'attachments'])
        );
    }

    /**
     * إنشاء طلب دورة جديد
     */
    public function store(Request $request)
    {
        // تحقق من صلاحية المستخدم لإنشاء الطلب
        $this->authorize('create', CourseRequest::class);

        // التحقق من البيانات المدخلة
        $data = $request->validate([
            'course_id' => 'nullable|exists:courses,id',  // تحقق من وجود الـ course_id في جدول الدورات
            'custom_course_title' => 'nullable|string|max:255',  // تحقق من صحة عنوان الدورة الخارجية
            'custom_course_provider' => 'nullable|string|max:255',  // تحقق من صحة الجهة المقدمة للدورة الخارجية
            'reason' => 'nullable|string|max:1000',  // تحقق من صحة السبب
        ]);

        // تحقق من أن المستخدم اختار دورة واحدة فقط (إما دورة موجودة أو دورة خارجية)
        $hasCourseId = $request->filled('course_id');
        $hasCustomCourse = $request->filled('custom_course_title');

        // التأكد من اختيار دورة واحدة فقط
        if (!$hasCourseId && !$hasCustomCourse) {
            return response()->json(['message' => 'يجب اختيار دورة موجودة أو إدخال دورة خارجية.'], 422);
        }

        if ($hasCourseId && $hasCustomCourse) {
            return response()->json(['message' => 'لا يمكن اختيار دورة موجودة وكتابة دورة خارجية في نفس الوقت.'], 422);
        }

        // التحقق من عدم تكرار الطلب لنفس الدورة من نفس المستخدم
        $user = auth()->user();

        // إذا كانت الدورة عبارة عن دورة موجودة في النظام
        if ($hasCourseId) {
            $existingRequest = CourseRequest::where('user_id', $user->id)
                ->where('course_id', $data['course_id'])
                ->first();

            if ($existingRequest) {
                return response()->json(['message' => 'لقد قدمت طلبًا لهذه الدورة مسبقًا.'], 422);
            }
        }

        // إذا كانت الدورة عبارة عن دورة خارجية
        if ($hasCustomCourse) {
            $existingRequest = CourseRequest::where('user_id', $user->id)
                ->whereNull('course_id')  // تأكد أن المستخدم لم يطلب دورة خارجية من قبل
                ->where('custom_course_title', $data['custom_course_title'])
                ->first();

            if ($existingRequest) {
                return response()->json(['message' => 'لقد قدمت طلبًا لدورة خارجية بنفس العنوان مسبقًا.'], 422);
            }
        }

        // إنشاء الطلب باستخدام الخدمة
        $courseRequest = $this->courseRequestService->create($data);

        // تحميل العلاقات المطلوبة مثل المستخدم والدورة والمرفقات
        return new CourseRequestResource($courseRequest->load(['user', 'course', 'attachments']));
    }

    /**
     * الموافقة على الطلب مع مرفق أو رابط اختياري
     */
    public function approve(Request $request, CourseRequest $courseRequest)
    {
        $this->authorize('approve', $courseRequest);

        // التحقق من البيانات المدخلة
        $data = $request->validate([
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
            'link' => 'nullable|url',
            'comment' => 'nullable|string|max:1000',
        ]);

        // الموافقة على الطلب
        $result = $this->courseRequestService->approve(
            $courseRequest,
            $data['attachment'] ?? null,
            $data['link'] ?? null,
            $data['comment'] ?? null
        );

        return new CourseRequestResource($result->load(['user', 'course', 'attachments']));
    }

    /**
     * رفض الطلب
     */
    public function reject(Request $request, CourseRequest $courseRequest)
    {
        $this->authorize('approve', $courseRequest);

        // التحقق من وجود تعليق عند الرفض
        $data = $request->validate(['comment' => 'required|string|max:1000']);

        // رفض الطلب
        $result = $this->courseRequestService->reject($courseRequest, $data['comment']);

        return new CourseRequestResource($result->load(['user', 'course', 'attachments']));
    }

    /**
     * التحقق من إذا كان المستخدم قد قدم طلبًا لهذه الدورة مسبقًا
     */
    public function checkIfAlreadyRegistered(Request $request, $courseId)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير متصل.'], 401); // Unauthorized
        }

        if (empty($courseId)) {
            return response()->json(['message' => 'معرف الدورة غير صالح.'], 400); // Bad Request
        }

        $exists = CourseRequest::where('user_id', $user->id)
                               ->where('course_id', $courseId)
                               ->exists();

        return response()->json(['exists' => $exists, 'message' => $exists ? 'لقد قدمت طلبًا لهذه الدورة مسبقًا.' : 'لم تقم بتقديم طلب لهذه الدورة.']);
    }

    /**
     * حذف طلب دورة
     */
    public function destroy(CourseRequest $courseRequest)
    {
        try {
            $this->courseRequestService->delete($courseRequest);
            return response()->json(['message' => 'تم حذف الطلب بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
    public function update(Request $request, $id)
{
    // التحقق من أن الـ CourseRequest موجود
    $courseRequest = CourseRequest::find($id);

    if (!$courseRequest) {
        return response()->json(['message' => 'طلب الدورة غير موجود'], 404);
    }

    // التحقق من صلاحية المستخدم لتعديل الطلب
    $this->authorize('update', $courseRequest);

    // التحقق من البيانات المدخلة
    $data = $request->validate([
        'course_id' => 'nullable|exists:courses,id',
        'custom_course_title' => 'nullable|string|max:255',
        'custom_course_provider' => 'nullable|string|max:255',
        'reason' => 'nullable|string|max:1000',
    ]);

    // تحقق إذا كانت الدورة الحالية أو دورة خارجية وتم تعيين الحقول بشكل مناسب
    $hasCourseId = $request->filled('course_id');
    $hasCustomCourse = $request->filled('custom_course_title');

    if (!$hasCourseId && !$hasCustomCourse) {
        return response()->json(['message' => 'يجب اختيار دورة موجودة أو إدخال دورة خارجية.'], 422);
    }

    // تحديث البيانات بناءً على المدخلات
    $courseRequest->update($data);

    // إعادة البيانات المعدلة
    return new CourseRequestResource($courseRequest->load(['user', 'course', 'attachments']));
}

}
