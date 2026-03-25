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
        Schema::create('galeria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategoria_id')->constrained('kategoriak')->onDelete('cascade');
            $table->string('fajlnev', 100)->nullable();
            $table->string('rovidleiras', 300)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria');
    }
};
