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
        Schema::table('statement_requests', function (Blueprint $table) {
            $table->text('comment')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('statement_requests', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }

};
