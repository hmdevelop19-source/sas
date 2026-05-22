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
        Schema::create('sp_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('sp_level')->unique(); // 1, 2, 3
            $table->integer('max_alpha'); // 3, 6, 9
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_settings');
    }
};
