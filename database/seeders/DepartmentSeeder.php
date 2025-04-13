<?php
// database/seeders/DepartmentSeeder.php
// database/seeders/DepartmentSeeder.php
// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء بعض الأقسام
        $departments = [
            ['name' => 'HR Department'],
            ['name' => 'IT Department'],
            ['name' => 'Marketing Department'],
            ['name' => 'Sales Department'],
        ];

        // إنشاء الأقسام
        foreach ($departments as $data) {
            $department = Department::firstOrCreate(['name' => $data['name']]);

            // تعيين مدير القسم (من الذين لديهم دور "manager" باستخدام Spatie)
            $manager = User::role('manager')->first();  // استخدام Spatie للبحث عن المدير

            if ($manager) {
                $department->manager_id = $manager->id;  // تعيين المدير للقسم
                $department->save();
            }
        }
    }
}
