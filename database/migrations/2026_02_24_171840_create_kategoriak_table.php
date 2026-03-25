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
        Schema::create('kategoriak', function (Blueprint $table) {
            $table->id();
            $table->string('nev', 100);
            $table->longText('leiras')->nullable();
            $table->string('icon', 100)->nullable();
            $table->boolean('szolgaltatas')->default(true);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoriak');
    }
};
