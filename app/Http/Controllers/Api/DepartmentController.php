<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * عرض قائمة الأقسام.
     */
    public function index()
    {
        $departments = Department::all();
        return DepartmentResource::collection($departments);
    }

    /**
     * عرض تفاصيل القسم.
     */
    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }

    /**
     * إنشاء قسم جديد.
     */
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

    /**
     * تحديث بيانات القسم.
     */
    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $updatedDepartment = $this->departmentService->updateDepartment($department, $data);
        return new DepartmentResource($updatedDepartment);
    }

    /**
     * حذف قسم.
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        $this->departmentService->deleteDepartment($department);
        return response()->json(null, 204);
    }
}
