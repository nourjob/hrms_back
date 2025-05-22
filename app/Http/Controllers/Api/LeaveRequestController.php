<?php
//app\Http\Controllers\Api\LeaveRequestController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    protected $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    /**
     * عرض جميع الطلبات الخاصة بالمستخدم.
     */
public function index(Request $request)
{
    $this->authorize('viewAny', LeaveRequest::class);

    // لا تمرر Auth::id() لأن getAll يستخدم auth()->user() مباشرة
    $requests = $this->leaveRequestService->getAll();

    return LeaveRequestResource::collection($requests);
}

    /**
     * عرض طلب معين
     */
   // عند جلب طلب الإجازة، تأكد من أن المرفقات يتم تحميلها بشكل صحيح
public function show(LeaveRequest $leaveRequest)
{
    $this->authorize('view', $leaveRequest);
    
    // تأكد من أن المرفقات موجودة في البيانات
    $leaveRequest->load('attachments'); // تأكد من تحميل المرفقات

    return new LeaveRequestResource($leaveRequest);
}


    /**
     * إنشاء طلب جديد
     */
public function store(Request $request)
{
    $this->authorize('create', LeaveRequest::class);

    // التحقق من البيانات المدخلة
    $data = Validator::make($request->all(), [
        'subtype' => 'required|in:study,medical,administrative',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:1000',
        'attachment' => [
            Rule::requiredIf(fn () => $request->subtype !== 'administrative'),
            'file',
            'mimes:pdf,jpg,jpeg,png',
            'max:20048', // 2MB
        ],
    ])->validate();

    $file = $request->file('attachment');

    // استخدام الخدمة لإنشاء الطلب
    $leaveRequest = $this->leaveRequestService->create($data, $file);

    return new LeaveRequestResource($leaveRequest);
}
  public function update(Request $request, LeaveRequest $leaveRequest)
{
    // التحقق من البيانات المدخلة
    $data = $request->validate([
        'subtype' => 'required|in:study,medical,administrative',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:1000',
    ]);

    // إذا كان هناك مرفق جديد
    $file = $request->file('attachment');

    // تحديث بيانات الطلب
    $leaveRequest->update($data);

    // إذا كان هناك مرفق جديد ولم تكن الإجازة إدارية
    if ($file && $data['subtype'] !== 'administrative') {
        // حذف المرفق القديم إذا كان موجودًا
        $leaveRequest->attachments()->delete();

        // تخزين المرفق الجديد
        $path = $file->store('attachments/leave_proofs', 'public');

        // حفظ المرفق الجديد في قاعدة البيانات
        Attachment::create([
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'uploaded_by' => Auth::id(),
            'attachable_type' => LeaveRequest::class,
            'attachable_id' => $leaveRequest->id,
        ]);
    }

    return new LeaveRequestResource($leaveRequest);
}

    /**
     * حذف الطلب
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $this->authorize('delete', $leaveRequest);

        $this->leaveRequestService->delete($leaveRequest);

        return response()->json(['message' => 'تم حذف الطلب بنجاح.']);
    }

    /**
     * الموافقة أو الرفض على الطلب
     */
/**
 * الموافقة أو الرفض على طلب الإجازة
 */
public function approve(Request $request, LeaveRequest $leaveRequest)
{
    // تحقق من صلاحية المستخدم (بناءً على Policy)
    $this->authorize('approve', $leaveRequest);

    // التحقق من صحة البيانات المدخلة
    $data = $request->validate([
        'status' => 'required|in:approved,rejected',
        'comment' => 'nullable|string|max:1000',
    ]);

    // تنفيذ منطق الموافقة أو الرفض عبر الخدمة
    $updated = $this->leaveRequestService->approve(
        $leaveRequest,
        $data['status'],
        $data['comment'] ?? null
        
    );

    // تحميل العلاقات المهمة من جديد
    $updated->load(['user', 'attachments', 'approvals.approver']);

    // رسالة ديناميكية حسب حالة الطلب
    $message = $data['status'] === 'approved'
        ? 'تمت الموافقة على الطلب بنجاح.'
        : 'تم رفض الطلب بنجاح.';

    // استجابة JSON واضحة
    return response()->json([
        'message' => $message,
        'status' => $updated->status,
        'request_id' => $updated->id,
        'data' => new LeaveRequestResource($updated),
    ]);
}

}
