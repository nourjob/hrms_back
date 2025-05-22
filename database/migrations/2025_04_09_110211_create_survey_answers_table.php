<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // أضف هذا السطر
            $table->id();

            $table->foreignId('survey_response_id')->constrained('survey_responses')->onDelete('cascade'); // إجابة كاملة
            $table->foreignId('survey_question_id')->constrained('survey_questions')->onDelete('cascade'); // السؤال
            $table->text('answer')->nullable(); // قيمة الإجابة (نص أو JSON)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
