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
        $department = Department::create([
            'name' => $data['name'],
        ]);

        // تعيين المدير للقسم إذا كان موجودًا
        if (isset($data['manager_id'])) {
            $department->manager_id = $data['manager_id'];
            $department->save();
        }

        return $department;
    }

    /**
     * تحديث بيانات القسم.
     *
     * @param  \App\Models\Department  $department
     * @param  array  $data
     * @return \App\Models\Department
     */
    public function updateDepartment(Department $department, array $data)
    {
        // $department->update($data);
        // return $department;
        if (isset($data['name'])) {
            $department->name = $data['name'];
        }
    
        if (array_key_exists('manager_id', $data)) {
            $department->manager_id = $data['manager_id'];
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
