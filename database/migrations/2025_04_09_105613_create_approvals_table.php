<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->morphs('approvable');
            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comment')->nullable();
            $table->timestamps(); // إضافة الأعمدة created_at و updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};

