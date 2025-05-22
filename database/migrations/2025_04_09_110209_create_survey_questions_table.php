<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            // تأكد من وجود الجدول قبل إضافة مفتاح خارجي
            if (Schema::hasTable('surveys')) {
                $table->foreignId('survey_id')
                    ->constrained('surveys')
                    ->onDelete('cascade');
            } else {
                // بديل آمن في حال جدول surveys غير موجود
                $table->unsignedBigInteger('survey_id');
                // يمكن لاحقًا إضافة constraint في Migration منفصل
            }

            $table->text('question_text');
            $table->enum('question_type', [
                'text',
                'multiple_choice',
                'rating',
                'boolean',
                'long_text',
                'multiple_boolean',
                'range',
                'date',
                'file'
            ])->default('text');
            $table->json('options')->nullable();
            $table->text('additional_data')->nullable();
            $table->boolean('required')->default(true);
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
