<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategoriak', function (Blueprint $table) {
            $table->longText('kiemelt_leiras')->nullable()->after('leiras');
        });
    }

    public function down(): void
    {
        Schema::table('kategoriak', function (Blueprint $table) {
            $table->dropColumn('kiemelt_leiras');
        });
    }
};
