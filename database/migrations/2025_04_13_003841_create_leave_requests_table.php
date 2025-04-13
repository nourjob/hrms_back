<?php
// database/migrations/xxxx_xx_xx_create_leave_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();  // معرف الطلب
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // ربط الطلب بالمستخدم
            $table->enum('subtype', ['study', 'medical', 'administrative']);  // نوع الإجازة
            $table->date('start_date');  // تاريخ بدء الإجازة
            $table->date('end_date');  // تاريخ نهاية الإجازة
            $table->text('reason');  // سبب الطلب
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // حالة الطلب
            $table->timestamps();  // تاريخ الإنشاء والتعديل
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
