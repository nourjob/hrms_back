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
            // إنشاء القسم مع تعيين `manager_id` كـ null
            $department = Department::firstOrCreate([
                'name' => $data['name'],
                'manager_id' => null,  // تعيين `manager_id` كـ null
            ]);
        }
    }
}
