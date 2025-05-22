<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\StatementRequestController;
use App\Http\Controllers\Api\CourseRequestController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\SurveyQuestionController;
use App\Http\Controllers\Api\SurveyAnswerController;
use App\Http\Controllers\Api\SurveyresponseController;
use App\Http\Controllers\RequestController;
   // تأكد من استيراد الـ AuthController
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 



    // مسار لاستعادة كلمة المرور
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

    // مسار لإعادة تعيين كلمة المرور
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    // مسار لتسجيل الخروج (محمي عبر Sanctum)
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


    Route::middleware('auth:sanctum')
    ->prefix('user')
    ->group(function () {
        // هنا أضف السطر التالي
        Route::post('/', [UserController::class, 'store']);

        // باقي الراوتات...
        Route::get('me', [UserController::class, 'me']);
        Route::get('{user}',    [UserController::class, 'show']);
        Route::get('/',         [UserController::class, 'index']);
        Route::put('update',    [UserController::class, 'updatePersonalData']);
        Route::put('{user}',    [UserController::class, 'update']);
        Route::delete('{user}', [UserController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->get('/managers', [UserController::class, 'getManagers']);

    Route::middleware('auth:sanctum')->prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::get('{department}', [DepartmentController::class, 'show']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::put('{department}', [DepartmentController::class, 'update']);
        Route::delete('{department}', [DepartmentController::class, 'destroy']);
        Route::get('{department}/employees', [DepartmentController::class, 'employees']); // ✅ إضافة هذا السطر
    }); 


// course controller
Route::middleware('auth:sanctum')->prefix('course')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::get('{course}', [CourseController::class,'show']);
    Route::post('/', [CourseController::class, 'store']);
    Route::put('{course}', [CourseController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('{course}', [CourseController::class, 'destroy']);
});


// course request controller
Route::middleware('auth:sanctum')->group(function () {
    // 🔹 الموارد الأساسية لطلبات الدورات
    Route::apiResource('course-requests', CourseRequestController::class);
    // 🔹 الموافقة على الطلب
    Route::post('course-requests/{courseRequest}/approve', [CourseRequestController::class, 'approve']);
    // 🔹 الرفض مع تعليق
    Route::post('course-requests/{courseRequest}/reject', [CourseRequestController::class, 'reject']);
});
Route::middleware('auth:sanctum')->get('/course-requests/check/{courseId}', [CourseRequestController::class, 'checkIfAlreadyRegistered']);


// إضافة مرفق للطلب
Route::middleware('auth:sanctum')->post('/attachments/{requestId}/{type}', [AttachmentController::class, 'store']);
// حذف مرفق
Route::middleware('auth:sanctum')->delete('/attachments/{attachment}', [AttachmentController::class, 'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    // CRUD للطلبات
    Route::apiResource('leave-requests', LeaveRequestController::class);
    // مسار خاص للموافقة على الطلب
    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
});


Route::middleware('auth:sanctum')->group(function () {
    // الموارد الأساسية
    Route::apiResource('statement-requests', StatementRequestController::class);
    // الموافقة مع رفع البيان PDF
    Route::post('statement-requests/{statementRequest}/approve', [StatementRequestController::class, 'approve']);
    // الرفض مع ملاحظات
    Route::post('statement-requests/{statementRequest}/reject', [StatementRequestController::class, 'reject']);
});


Route::middleware('auth:sanctum')->group(function () {

    // 🔹 استبيانات
    Route::apiResource('surveys', SurveyController::class);

    // 🔹 الأسئلة المتعلقة بكل استبيان
    Route::apiResource('surveys.questions', SurveyQuestionController::class); // التحكم بأسئلة الاستبيانات
    
    // 🔹 استجابات الاستبيانات
    Route::apiResource('survey-responses', SurveyResponseController::class);

    // 🔹 عرض نتائج الاستبيان (تقرير)
    Route::prefix('survey-responses/{response}')->group(function () {
        Route::post('/answers', [SurveyAnswerController::class, 'store']);
        Route::get('/answers/{answer}', [SurveyAnswerController::class, 'show']);
    });
    Route::middleware(['auth:sanctum'])->get('/requests/all', [RequestController::class, 'getAllFilteredRequests']);

});


//Route::get('survey-results/{survey}', [SurveyResultController::class, 'index']);
