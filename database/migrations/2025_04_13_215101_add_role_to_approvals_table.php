<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->string('role')->after('approved_by'); // أو enum لو تحب تحديد القيم
        });
    }

    public function down()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

};
