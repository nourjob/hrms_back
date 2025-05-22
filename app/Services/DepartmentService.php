<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService
{
    /**
     * إنشاء قسم جديد.
     *
     * @param  array  $data
     * @return \App\Models\Department
     */
    public function createDepartment(array $data)
    {
        // Check if manager is already assigned to another department
        if (isset($data['manager_id'])) {
            $existing = Department::where('manager_id', $data['manager_id'])->first();
            if ($existing) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'manager_id' => 'This manager is already assigned to another department.',
                ]);
            }
        }

        $department = Department::create([
            'name' => $data['name'],
            'manager_id' => $data['manager_id'] ?? null,
        ]);

        return $department;
    }

    public function updateDepartment(Department $department, array $data)
    {
        if (isset($data['name'])) {
            $department->name = $data['name'];
        }
    
        if (array_key_exists('manager_id', $data)) {
            // فقط إذا تغير المدير
            if ($data['manager_id'] !== $department->manager_id) {
    
                // إلغاء ربط المدير الحالي إن وجد
                if ($department->manager_id) {
                    \App\Models\User::where('id', $department->manager_id)
                        ->update(['department_id' => null]);
                }
    
                // إذا كان هناك مدير جديد، تحقق من عدم تكراره
                if ($data['manager_id'] !== null) {
                    $existing = Department::where('manager_id', $data['manager_id'])
                        ->where('id', '!=', $department->id)
                        ->first();
    
                    if ($existing) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'manager_id' => 'This manager is already assigned to another department.',
                        ]);
                    }
    
                    // ربط المدير الجديد بالقسم
                    \App\Models\User::where('id', $data['manager_id'])
                        ->update(['department_id' => $department->id]);
                }
    
                // تحديث قيمة المدير (قد تكون null)
                $department->manager_id = $data['manager_id'];
            }
        }
    
        $department->save();
    
        return $department;
    }
    



    /**
     * حذف القسم.
     *
     * @param  \App\Models\Department  $department
     * @return bool|null
     */
    public function deleteDepartment(Department $department)
    {
        return $department->delete();
    }
}
