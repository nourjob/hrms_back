<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->string('title'); // عنوان الاستبيان
            $table->text('description')->nullable(); // وصف أو تعليمات

            $table->enum('type', ['internal', 'external'])->default('internal'); // نوع الاستبيان
            $table->string('url')->nullable(); // رابط خارجي في حال النوع external

            $table->foreignId('target_department_id')->nullable()->constrained('departments')->nullOnDelete(); // استهداف قسم
            $table->json('target_roles')->nullable(); // أدوار مستهدفة (admin, hr, employee...)

            $table->boolean('is_active')->default(false); // هل مفعل أم لا

            $table->date('start_date')->nullable(); // بداية النشر
            $table->date('end_date')->nullable();   // نهاية النشر

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // من أنشأ الاستبيان

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
