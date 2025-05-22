<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // أضف هذا السطر
            $table->id();

            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');   // من هو الموظف الذي أجاب

            $table->timestamp('submitted_at')->nullable(); // وقت الإرسال (null إذا لم يُرسل بعد)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
