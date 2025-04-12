<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseRequest;
use App\Models\Course;
use App\Models\Approval;
use Illuminate\Http\Request;

class CourseRequestController extends Controller
{
    // عرض جميع طلبات الدورات
    public function index()
    {
        $courseRequests = CourseRequest::all();
        return response()->json($courseRequests);
    }

    // إضافة طلب دورة جديد
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string',
            'description' => 'required|string',
        ]);

        // إضافة طلب الدورة
        $courseRequest = CourseRequest::create([
            'course_name' => $request->course_name,
            'description' => $request->description,
            'user_id' => 1,  // يمكننا تحديد الـ user_id يدويًا (على سبيل المثال المستخدم الأول)
            'status' => 'pending', // يتم تعيين الحالة "معلق" افتراضيًا
        ]);

        return response()->json($courseRequest, 201);
    }

    // عرض تفاصيل طلب دورة معين
    public function show($id)
    {
        $courseRequest = CourseRequest::find($id);
        if (!$courseRequest) {
            return response()->json(['message' => 'Course request not found'], 404);
        }
        return response()->json($courseRequest);
    }
   // تعديل أو تحديث طلب الدورة
public function update(Request $request, $id)
{
    // التحقق من وجود الطلب
    $courseRequest = CourseRequest::find($id);

    if (!$courseRequest) {
        return response()->json(['message' => 'Course request not found'], 404);
    }

    // التحقق من صحة البيانات
    $request->validate([
        'course_name' => 'sometimes|required|string',
        'description' => 'sometimes|required|string',
        'status' => 'sometimes|required|in:pending,approved,rejected',
    ]);

    // تحديث بيانات الطلب بناءً على المُدخلات
    $courseRequest->update($request->only([
        'course_name',
        'description',
        'status'
    ]));

    return response()->json($courseRequest);
}
// حذف طلب دورة معين
public function destroy($id)
{
    $courseRequest = CourseRequest::find($id);

    if (!$courseRequest) {
        return response()->json(['message' => 'Course request not found'], 404);
    }

    $courseRequest->delete();

    return response()->json(['message' => 'Course request deleted successfully']);
}
// عرض طلبات الدورة للمستخدم الحالي
public function myRequests()
{
    $courseRequests = CourseRequest::where('user_id', auth()->id())->get();

    return response()->json($courseRequests);
}
// عرض الطلبات حسب الحالة
public function filterByStatus($status)
{
    if (!in_array($status, ['pending', 'approved', 'rejected'])) {
        return response()->json(['message' => 'Invalid status provided'], 400);
    }

    $courseRequests = CourseRequest::where('status', $status)->get();

    return response()->json($courseRequests);
}
// البحث في الطلبات حسب اسم الدورة
public function search(Request $request)
{
    $request->validate([
        'query' => 'required|string',
    ]);

    $courseRequests = CourseRequest::where('course_name', 'LIKE', '%' . $request->query . '%')->get();

    return response()->json($courseRequests);
}




}
