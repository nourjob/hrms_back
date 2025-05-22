<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

public function index(Request $request)
{
    $authUser = auth()->user();

    $this->authorize('viewAny', User::class);

    // عدد العناصر في الصفحة، يمكن تمريرها عبر query param ?per_page=10 مثلا
    $perPage = $request->input('per_page', 15); // 15 هو الافتراضي

    // استدعاء السيرفيس مع pagination
    $users = $this->userService->getUsersVisibleToPaginated($authUser, $perPage);

    // إرجاع كائن Resource مع pagination
    return UserResource::collection($users);
}



    public function show(User $user)
    {
        // تحميل العلاقات department و manager و roles مع المستخدم
        $user->load(['department', 'manager', 'roles']);  // إضافة 'roles' هنا

        return new UserResource($user);  // تحويل بيانات المستخدم إلى JSON
    }

    /**
     * إنشاء مستخدم جديد.
     */
 public function store(Request $request)
{
    $authUser = auth()->user();

    $this->authorize('create', User::class);

    $data = $request->validate([
        'name' => 'required|string',
        'username' => 'required|string|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|exists:roles,name',
        'job_number' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'status' => 'nullable|string',
        'marital_status' => 'nullable|string',
        'number_of_children' => 'nullable|integer',
        'qualification' => 'nullable|string',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'university' => 'nullable|string',
        'graduation_year' => 'nullable|string',
    ]);

    if ($authUser->hasRole('hr') && !in_array(strtolower($data['role']), ['manager', 'employee'])) {
        abort(403, 'HR can only create users with role manager or employee.');
    }

    $user = $this->userService->createUser($data, $authUser);

    return new UserResource($user);
}

    /**
     * تحديث بيانات المستخدم العامة.
     */
   public function update(Request $request, User $user)
{
    $authUser = auth()->user();

    $this->authorize('update', $user);

    $data = $request->validate([
        'username' => 'required|string|unique:users,username,' . $user->id,
        'password' => 'nullable|string|min:8',
        'role' => 'required|string|exists:roles,name',
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'job_number' => 'nullable|string|unique:users,job_number,' . $user->id,
        'department_id' => 'nullable|exists:departments,id',
        'status' => 'nullable|string|in:active,suspended,resigned',
        'marital_status' => 'nullable|string',
        'number_of_children' => 'nullable|integer',
        'qualification' => 'nullable|string',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'university' => 'nullable|string',
        'graduation_year' => 'nullable|string',
    ]);

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $updatedUser = $this->userService->updateEmployeeData($user, $data, $authUser);

    return new UserResource($updatedUser);
}

    public function me(Request $request)
    {
        $user = $request->user();

        // تحميل العلاقات المهمة
        $user->load(['department', 'manager', 'roles']);

        return new UserResource($user);
    }


    /**
     * تعديل البيانات الشخصية (يحق فقط للموظف).
     */
    public function updatePersonalData(Request $request)
    {
        $user = Auth::user();

        // ✅ التحقق من أن المستخدم هو الموظف فقط
        if (! $user->hasRole('employee')) {
            abort(403, 'Only employees can update their personal data.');
        }

        // ✅ التحقق من إمكانية تحديث المستخدم لملفه الشخصي
        $this->authorize('updateProfile', $user);

        // ✅ التحقق من صحة البيانات الشخصية
        $data = $request->validate([
            'marital_status' => 'nullable|string',
            'number_of_children' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'university' => 'nullable|string',
            'graduation_year' => 'nullable|string',
        ]);

        // ✅ تحديث البيانات الشخصية
        $user->update($data);

        // إرسال إشعار للـ HR والمدير بأن الموظف قام بتحديث بياناته
        //$this->sendUpdateNotification($user);

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
        $managers = User::role('manager')->get();
        $hr = User::role('hr')->get();

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
    $authUser = auth()->user();

    $this->authorize('delete', $user);

    $this->userService->deleteUser($user, $authUser);

    return response()->json(null, 204);
}


    /**
     * عرض قائمة المستخدمين الذين لديهم دور "manager".
     *
     * @return \Illuminate\Http\Response
     */
    public function getManagers()
    {
        $managers = $this->userService->getManagers();
        return response()->json($managers);
    }
}







