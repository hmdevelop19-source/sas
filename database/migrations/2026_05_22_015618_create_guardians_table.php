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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('education_id')->nullable()->constrained('education')->nullOnDelete();
            $table->foreignId('occupation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nkk', 16)->nullable();
            $table->string('nik', 16)->unique()->nullable();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('citizenship')->default('WNI');
            $table->string('phone')->nullable();
            $table->string('relationship')->nullable(); // e.g., Ayah, Ibu, Wali
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
