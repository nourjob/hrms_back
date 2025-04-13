<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // تحميل العلاقات department و manager مع جميع المستخدمين
        $users = User::with(['department', 'manager'])->get();  // تحميل القسم والمدير

        return UserResource::collection($users);  // تحويل البيانات إلى JSON باستخدام UserResource
    }

    public function show(User $user)
    {
        // تحميل العلاقات department و manager مع المستخدم الفردي
        $user->load(['department', 'manager']);  // تحميل القسم والمدير مع المستخدم

        return new UserResource($user);  // تحويل بيانات المستخدم إلى JSON
    }

    /**
     * إنشاء مستخدم جديد.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class); // تحقق من صلاحية المستخدم

        $data = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
            'job_number' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            // ✅ Add these optional fields:
            'status' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'number_of_children' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'university' => 'nullable|string',
            'graduation_year' => 'nullable|string',
        ]);

        $user = $this->userService->createUser($data);  // إنشاء المستخدم باستخدام الخدمة

        return new UserResource($user);  // إرجاع البيانات باستخدام UserResource
    }

    /**
     * تحديث بيانات المستخدم العامة.
     */
    public function updateEmployeeData(Request $request, User $user)
    {
        $user = Auth::user();  // هذا هو المكان الذي نقوم فيه بتعيين المتغير `$user`

        $this->authorize('updateProfile', $user);  // تحقق من صلاحية الـ Admin أو HR لتحديث البيانات

        $data = $request->validate([
            'name' => 'nullable|string',
            'username' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'job_number' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'nullable|string|in:active,suspended,resigned',
            'role' => 'nullable|string|in:admin,hr,manager,employee', // لا بد من تحديد الأدوار المتاحة
        ]);

        $updatedUser = $this->userService->updateEmployeeData($user, $data);  // تحديث البيانات العامة للمستخدم

        return new UserResource($updatedUser);  // إرجاع البيانات باستخدام UserResource
    }

    /**
     * تعديل البيانات الشخصية (يحق فقط للموظف).
     */
    public function updatePersonalData(Request $request)
    {
        $user = Auth::user();  // هذا هو المكان الذي نقوم فيه بتعيين المتغير `$user`

        $this->authorize('updateProfile', $user);  // استخدم الـ Policy للتحقق إذا كان لدى المستخدم صلاحية تعديل بياناته

        // التحقق من الحقول التي يحق للموظف تعديلها فقط
        $data = $request->validate([
            'marital_status' => 'nullable|string',
            'number_of_children' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'university' => 'nullable|string',
            'graduation_year' => 'nullable|string',
        ]);

        // تحديث بيانات الموظف
        $user->update($data);

        // إرسال إشعار للـ HR والمدير بأن الموظف قام بتحديث بياناته
        $this->sendUpdateNotification($user);

        return response()->json([
            'message' => 'Your personal data has been updated successfully.',
            'user' => $user
        ]);
    }

    /**
     * إرسال إشعار للـ HR والمدير بتحديث بيانات الموظف.
     */
    private function sendUpdateNotification($user)
    {
        $managers = User::where('role', 'manager')->get();
        $hr = User::where('role', 'hr')->get();

        $notificationData = [
            'title' => 'Profile Updated',
            'body' => $user->name . ' has updated their personal profile.',
            'type' => 'profile_update',
        ];

        foreach ($managers as $manager) {
            $manager->notifications()->create($notificationData);
        }

        foreach ($hr as $hrUser) {
            $hrUser->notifications()->create($notificationData);
        }
    }

    /**
     * حذف المستخدم.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);  // تحقق من صلاحية المستخدم

        $this->userService->deleteUser($user);  // حذف المستخدم باستخدام الخدمة

        return response()->json(null, 204);  // إرجاع استجابة فارغة
    }
}
