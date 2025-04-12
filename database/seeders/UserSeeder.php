<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;  // إضافة هذا السطر لاستيراد Role من مكتبة Spatie
use App\Models\Department;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود الأدوار في البداية
        $this->createRoles();

        // بيانات المستخدمين
        $users = [
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'password',
                'job_number' => 'A1001',
                'role' => 'admin',
            ],
            [
                'name' => 'HR User',
                'username' => 'hr',
                'email' => 'hr@example.com',
                'password' => 'password',
                'job_number' => 'H2001',
                'role' => 'hr',
            ],
            [
                'name' => 'Manager User',
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => 'password',
                'job_number' => 'M3001',
                'role' => 'manager',
            ],
            [
                'name' => 'Employee User',
                'username' => 'employee',
                'email' => 'employee@example.com',
                'password' => 'password',
                'job_number' => 'E4001',
                'role' => 'employee',
            ],
        ];

        // إنشاء المستخدمين
        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                    'job_number' => $data['job_number'],
                    'status' => 'active',
                    'marital_status' => 'single',
                    'number_of_children' => 0,
                    'qualification' => 'Bachelor',
                    'phone' => '0500000000',
                    'address' => 'Default Address',
                    'university' => 'Default University',
                    'graduation_year' => '2020',
                ]
            );

            // تعيين الدور للمستخدم
            $user->assignRole($data['role']);
        }
    }

    // دالة لإنشاء الأدوار
    private function createRoles()
    {
        // تأكد من وجود الأدوار
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'hr']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'employee']);
    }
}
