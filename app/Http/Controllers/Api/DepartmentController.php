<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Contrllers\UserController;
use App\Http\Resources\UserResource;
class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function employees(Department $department)
{
    // 🔒 تحقق من صلاحيات المستخدم (admin أو hr)
    if (!auth()->user()->hasAnyRole(['admin', 'hr'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // 🧑‍🤝‍🧑 جلب المستخدمين في هذا القسم
    $employees = $department->users()->with('roles')->get();

    return UserResource::collection($employees);
}

    
    public function index()
    {
        $departments = Department::with('manager')->get(); // تأكد من تحميل العلاقة manager

        return DepartmentResource::collection($departments);
    }


    
    public function show(Department $department)
    {
        $this->authorize('view', $department);  // 🔒 Only admin or department's manager
        return new DepartmentResource($department);
    }

    
    public function store(Request $request)
    {
        $this->authorize('create', Department::class);

        $data = $request->validate([
            'name' => 'required|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department = $this->departmentService->createDepartment($data);
        return new DepartmentResource($department);
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $data = $request->validate([
            'name' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $updatedDepartment = $this->departmentService->updateDepartment($department, $data);
        return new DepartmentResource($updatedDepartment);
    }

    
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        $this->departmentService->deleteDepartment($department);
        return response()->json(null, 204);
    }
 

}
