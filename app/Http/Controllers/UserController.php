<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * عرض قائمة المستخدمين.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class); // تحقق من صلاحية المستخدم

        $users = User::all();  // جلب جميع المستخدمين
        return UserResource::collection($users);  // تحويل البيانات إلى JSON باستخدام UserResource
    }

    /**
     * عرض تفاصيل المستخدم.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user); // تحقق من صلاحية المستخدم

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
     * تحديث بيانات المستخدم.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);  // تحقق من صلاحية المستخدم

        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        $updatedUser = $this->userService->updateUser($user, $data);  // تحديث بيانات المستخدم

        return new UserResource($updatedUser);  // إرجاع البيانات باستخدام UserResource
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
