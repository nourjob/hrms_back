<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء Seeder الخاص بالأدوار والصلاحيات
        $this->call([
             // استدعاء PermissionSeeder
             PermissionSeeder::class,
             UserSeeder::class,
             DepartmentSeeder::class,
                 // استدعاء UserSeeder
        ]);
    }
}
