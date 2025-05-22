<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()    
    {
        Schema::create('course_requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            // صاحب الطلب
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // دورة موجودة في النظام (اختياري)
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');

            // معلومات الدورة الخارجية (اختياري)
            $table->string('custom_course_title')->nullable();
            $table->string('custom_course_provider')->nullable();

            // سبب الطلب
            $table->text('reason')->nullable();

            // الحالة
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // تعليق HR أو سبب الرفض
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

};
