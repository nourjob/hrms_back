<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->morphs('attachable');
            $table->string('file_path');
            $table->string('file_type');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
}; 
