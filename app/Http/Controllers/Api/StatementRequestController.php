<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatementRequestResource;
use App\Models\StatementRequest;
use App\Services\StatementRequestService;
use Illuminate\Http\Request;

class StatementRequestController extends Controller
{
    protected $statementService;

    public function __construct(StatementRequestService $statementService)
    {
        $this->statementService = $statementService;
    }

    /**
     * عرض جميع الطلبات
     */
    public function index()
    {
        $this->authorize('viewAny', StatementRequest::class);

        $requests = $this->statementService->getAll(auth()->user()->hasRole('hr') ? null : auth()->id());

        // تحميل العلاقات
        $requests->load(['user', 'attachments']);

        return StatementRequestResource::collection($requests);
    }

    /**
     * عرض طلب معين
     */
    public function show(StatementRequest $statementRequest)
    {
        $this->authorize('view', $statementRequest);

        return new StatementRequestResource(
            $statementRequest->load(['user', 'attachments'])
        );
    }

    /**
     * إنشاء طلب بيان جديد
     */
    public function store(Request $request)
    {
        $this->authorize('create', StatementRequest::class);

        $data = $request->validate([
            'subtype' => 'required|in:salary,status',
            'reason' => 'nullable|string|max:1000',
        ]);

        $statement = $this->statementService->create($data);

        return new StatementRequestResource($statement->load(['user', 'attachments']));
    }

    /**
     * الموافقة على الطلب من قبل HR مع إرفاق البيان PDF
     */
    public function approve(Request $request, StatementRequest $statementRequest)
    {
        $this->authorize('approve', $statementRequest);

        $data = $request->validate([
            'attachment' => 'required|file|mimes:pdf|max:2048',
            'comment' => 'nullable|string|max:1000',
        ]);

        $result = $this->statementService->approve($statementRequest, $data['attachment'], $data['comment'] ?? null);

        return new StatementRequestResource($result->load(['user', 'attachments']));
    }

    /**
     * رفض الطلب
     */
  // app/Http/Controllers/Api/StatementRequestController.php

public function reject(Request $request, StatementRequest $statementRequest)
{
    $this->authorize('reject', $statementRequest);

    $data = $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $result = $this->statementService->reject($statementRequest, $data['comment']);

    return new StatementRequestResource($result->load(['user', 'attachments']));
}

    public function update(Request $request, StatementRequest $statementRequest)
{
    // التحقق من صلاحية المستخدم
    $this->authorize('update', $statementRequest);

    // التحقق من البيانات المدخلة وتحديثها
    $data = $request->validate([
        'subtype' => 'required|in:salary,status',  // تحقق من نوع البيان
        'reason' => 'nullable|string|max:1000',    // تحقق من السبب
    ]);

    // تحديث البيانات
    $statementRequest->update($data);

    // إعادة البيانات المعدلة مع العلاقات
    return new StatementRequestResource($statementRequest->load(['user', 'attachments']));
}
/**
 * حذف طلب بيان معين
 */
public function destroy(StatementRequest $statementRequest)
{
    // التحقق من صلاحية الحذف
    $this->authorize('delete', $statementRequest);

    // حذف الطلب
    $statementRequest->delete();

    // رد نجاح مع رسالة (يمكن تعديلها حسب الحاجة)
    return response()->json([
        'message' => 'تم حذف طلب البيان بنجاح.'
    ], 200);
}

}
