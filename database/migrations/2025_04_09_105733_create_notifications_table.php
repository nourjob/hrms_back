<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type', ['info', 'request', 'approval', 'system']);
            $table->string('related_type');
            $table->unsignedBigInteger('related_id');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    } 

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
