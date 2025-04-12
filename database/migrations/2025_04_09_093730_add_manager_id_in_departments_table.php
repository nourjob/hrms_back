<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // migration to add manager_id in departments table
public function up(): void
{
    Schema::table('departments', function (Blueprint $table) {
        $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();  // ربط المدير
    });
}

public function down(): void
{
    Schema::table('departments', function (Blueprint $table) {
        $table->dropForeign(['manager_id']);
        $table->dropColumn('manager_id');
    });
}

};
