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
        Schema::create('cikkek', function (Blueprint $table) {
            $table->id();
            $table->string('cim');
            $table->string('slug')->unique();
            $table->text('bevezeto');
            $table->longText('tartalom');
            $table->string('boritekep')->nullable();
            $table->string('meta_leiras', 320)->nullable();
            $table->string('kulcsszavak', 500)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cikkek');
    }
};
