<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('job_number')->unique();
            $table->enum('status', ['active', 'suspended', 'resigned'])->default('active');
                // ربط المستخدم بالقسم
        $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

        // ربط المستخدم بالمدير داخل نفس الجدول
        $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();  // ربط المستخدم بالمدير

            //      الأعمدة الجديدة المطلوبة القابلة للتعديل عن طريق المستخدم
            $table->string('marital_status')->nullable();     // الحالة الاجتماعية (مثلاً: أعزب، متزوج)
            $table->integer('number_of_children')->nullable();// عدد الأولاد
            $table->string('qualification')->nullable();      // المؤهلات العلمية
            $table->string('phone')->nullable();              // رقم الهاتف
            $table->string('address')->nullable();
            $table->string('university')->nullable();       // العنوان
            $table->string('graduation_year')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
