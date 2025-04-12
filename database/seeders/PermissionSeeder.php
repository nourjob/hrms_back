<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;  // استيراد Role من مكتبة Spatie
use Spatie\Permission\Models\Permission;  // استيراد Permission من مكتبة Spatie

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // قائمة الصلاحيات
        $permissions = [
            // 👤 المستخدمين
            'view users',
            'create users',
            'edit users',
            'delete users',

            // 📄 الطلبات (إجازات + بيانات)
            'view requests',
            'create requests',
            'edit own requests',
            'delete own requests',
            'approve requests',
            'reject requests',

            // 🧾 أنواع الطلبات
            'request leave',
            'request statement',

            // 🧑‍⚕️ تفاصيل الإجازة
            'upload leave proof',

            // 📚 الدورات التدريبية
            'view courses',
            'edit courses',
            'create course',
            'update course',
            'request course',
            'approve course request',
            'reject course request',

            // 🧍 التحديثات الشخصية
            'update personal info',
            'upload attachments',

            // 📊 التقارير
            'view reports',
            'generate reports',

            // 📅 الحضور والإجازات
            'view attendance',
            'edit attendance',
            'approve leave',

            // 📝 الاستبيانات
            'create surveys',
            'view surveys',
            'submit survey',
            'view survey results',

            // 🏢 الأقسام (Departments)
            'view departments',
            'create departments',
            'update departments',
            'delete departments',

            // ⚙️ إعدادات النظام
            'manage roles',
            'assign roles',
            'view audit logs',
        ];

        // إنشاء الصلاحيات إن لم تكن موجودة
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // ربط الأدوار بالصلاحيات

        // منح جميع الصلاحيات لدور admin
        $adminRole->givePermissionTo(Permission::all());

        // منح صلاحيات لـ hr
        $hrRole->givePermissionTo([
            'view users', 'edit users',
            'view requests', 'approve requests', 'reject requests',
            'view reports', 'generate reports',
            'view attendance', 'edit attendance', 'approve leave',
            'view courses', 'approve course request', 'reject course request',
            'create surveys', 'view surveys', 'view survey results',
            'view departments', 'create departments',
        ]);

        // منح صلاحيات لـ manager
        $managerRole->givePermissionTo([
            'view users', 'edit users',
            'view requests', 'approve requests', 'reject requests',
            'view reports', 'generate reports',
            'view attendance', 'edit attendance', 'approve leave',
            'view courses', 'approve course request', 'reject course request',
            'create surveys', 'view surveys', 'view survey results',
        ]);

        // منح صلاحيات لـ employee
        $employeeRole->givePermissionTo([
            'create requests', 'edit own requests', 'delete own requests', 'view requests',
            'request leave', 'request statement', 'upload leave proof',
            'view courses', 'request course',
            'update personal info', 'upload attachments',
            'view surveys', 'submit survey',
        ]);
    }
}
