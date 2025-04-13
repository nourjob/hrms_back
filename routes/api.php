<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AttachmentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('auth')->group(function () {
    // مسار لتسجيل الدخول
    Route::post('login', [AuthController::class, 'login']);

    // مسار لاستعادة كلمة المرور
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

    // مسار لإعادة تعيين كلمة المرور
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    // مسار لتسجيل الخروج (محمي عبر Sanctum)
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);

});
// routes/api.php

// Route لتعديل البيانات العامة (يحق للـ Admin و HR فقط)
Route::middleware('auth:sanctum')->put('/users/{user}/update', [UserController::class, 'updateEmployeeData']);

// Route لتعديل البيانات الشخصية (يحق للموظف فقط)
Route::middleware('auth:sanctum')->put('/user/update', [UserController::class, 'updatePersonalData']);

Route::middleware('auth:sanctum')->prefix('departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('{department}', [DepartmentController::class, 'show']);
    Route::post('/', [DepartmentController::class, 'store']);
    Route::put('{department}', [DepartmentController::class, 'update']);
    Route::delete('{department}', [DepartmentController::class, 'destroy']);
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


Route::middleware('auth:sanctum')->prefix('course-request')->group(function () {
    Route::get('/', [CourseRequestController::class,'index']);
    Route::get('{course_request}', [CourseRequestController::class,'show']);
    Route::post('/', [CourseRequestController::class,'store']);
    Route::put('{course_request}', [CourseRequestController::class,'update']);
    Route::delete('{course_request}', [CourseRequestController::class,'destroy']);
});
// routes/api.php



// إضافة مرفق للطلب
Route::middleware('auth:sanctum')->post('/attachments/{requestId}/{type}', [AttachmentController::class, 'store']);

// حذف مرفق
Route::middleware('auth:sanctum')->delete('/attachments/{attachment}', [AttachmentController::class, 'destroy']);
