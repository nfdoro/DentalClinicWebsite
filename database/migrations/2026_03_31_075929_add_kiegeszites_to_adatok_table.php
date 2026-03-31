<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adatok', function (Blueprint $table) {
            $table->string('kiegeszites', 200)->nullable()->after('ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adatok', function (Blueprint $table) {
            $table->dropColumn('kiegeszites');
        });
    }
};
