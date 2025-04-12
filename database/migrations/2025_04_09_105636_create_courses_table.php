<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // اسم الدورة
            $table->text('description')->nullable(); // وصف الدورة (يمكن أن يكون فارغًا)
            $table->date('start_date');            // تاريخ البداية
            $table->date('end_date');              // تاريخ النهاية
            $table->string('location')->nullable();            // مكان الدورة
            $table->string('instructor');          // اسم المدرب
            $table->boolean('available')->default(true); // هل الدورة متاحة؟
            $table->timestamps();                 // تاريخ ووقت الإنشاء والتحديث
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
