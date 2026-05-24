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
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['occupation_id']);
            $table->dropColumn(['nkk', 'religion', 'occupation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('occupation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nkk', 16)->nullable();
            $table->string('religion')->nullable();
        });
    }
};
