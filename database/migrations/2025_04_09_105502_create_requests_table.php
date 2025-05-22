<?php
// database/migrations/xxxx_xx_xx_create_requests_table.php

// database/migrations/xxxx_xx_xx_create_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['leave', 'statement']); // نوع الطلب
            $table->enum('subtype', ['study', 'medical', 'administrative', 'salary', 'status']);
            $table->date('start_date')->nullable(); // تاريخ بداية الإجازة
            $table->date('end_date')->nullable(); // تاريخ نهاية الإجازة
            $table->text('reason')->nullable(); // سبب الطلب
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('proof_file')->nullable(); // حقل الإثبات

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

