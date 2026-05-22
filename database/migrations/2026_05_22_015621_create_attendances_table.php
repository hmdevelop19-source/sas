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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kuartal_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('status'); // 1: Hadir, 2: Izin, 3: Sakit, 4: Alpha
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index gabungan untuk mempercepat query performa
            $table->index(['student_id', 'kuartal_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
