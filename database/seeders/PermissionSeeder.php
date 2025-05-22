<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // 👤 المستخدمين
            'view users', 'create users', 'edit users', 'delete users',

            // 📄 الطلبات (إجازات)
            'view any leave requests', 'view leave requests',
            'request leave', 'edit own leave request', 'delete leave request',
            'approve leave',

            // 🧾 طلبات البيان
            'view statement requests',
            'create statement request',
            'approve statement request',
            'reject statement request',
            'request statement',

            // 📥 طلبات الدورات
            'view course requests',
            'create course request',
            'approve course request',
            'reject course request',

            // 📚 إدارة الدورات التدريبية
            'view courses',
            'create course',
            'update course',
            'delete course',

            // 🧾 إثبات الإجازة
            'upload leave proof',

            // 🧍 التحديثات الشخصية
            'update personal info', 'update personal data',
            'update employee data', 'upload attachments',

            // 📊 التقارير
            'view reports', 'generate reports',

            // 📅 الحضور والإجازات
            'view attendance', 'edit attendance',

            // 📝 الاستبيانات
            'create surveys', 'view surveys', 'submit survey', 'view survey results',

            // 🧑‍🏫 الأسئلة والإجابات
            'create survey questions', 'view survey questions', 'update survey questions', 'delete survey questions', // صلاحيات الأسئلة
            'create survey answers', 'view survey answers', // صلاحيات الإجابات

            // 🏢 الأقسام (Departments)
            'view departments', 'create departments', 'update departments', 'delete departments',

            // ⚙️ إعدادات النظام
            'manage roles', 'assign roles', 'view audit logs',
        ];

        // إضافة الصلاحيات إذا لم تكن موجودة
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إضافة الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // 🔐 admin: كل الصلاحيات
        $adminRole->givePermissionTo(Permission::all());

        // 🔐 HR
        $hrRole->givePermissionTo([
            'view users', 'edit users',
            'view any leave requests', 'view leave requests',
            'approve leave',

            // طلبات البيان
            'view statement requests',
            'approve statement request',
            'reject statement request',

            // إدارة الدورات
            'view courses',
            'create course',
            'update course',
            'delete course',

            // طلبات الدورات
            'view course requests',
            'approve course request',
            'reject course request',

            'view reports', 'generate reports',
            'view attendance', 'edit attendance',
            'create surveys', 'view surveys', 'view survey results',
            'view departments', 'create departments',
            'update employee data',

            // صلاحيات الأسئلة والإجابات
            'create survey questions', 'view survey questions', 'update survey questions', 'delete survey questions', // الأسئلة
            'create survey answers', 'view survey answers', // الإجابات
        ]);

        // 🔐 Manager
        $managerRole->givePermissionTo([
            'view users',
            'view any leave requests', 'view leave requests',
            'approve leave',
            'view reports', 'generate reports',
            'view attendance', 'edit attendance',
            'view courses',
            'approve course request',
            'reject course request',
            'view surveys', 'view survey results',
        ]);

        // 🔐 Employee
        $employeeRole->givePermissionTo([
            'request leave', 'edit own leave request', 'delete leave request',
            'view leave requests',
           
            // طلبات البيان
            'view statement requests',
            'create statement request',

            // طلبات الدورات
            'view course requests',
            'create course request',

            'view courses',
            'upload leave proof',
            'update personal data',
            'update personal info',
            'upload attachments',
            'view surveys',
            'submit survey',

            // صلاحيات الإجابة على الأسئلة
            'create survey answers', 'view survey answers',
        ]);
    }
}
