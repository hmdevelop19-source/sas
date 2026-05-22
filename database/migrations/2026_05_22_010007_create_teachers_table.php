<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('education_id')->nullable()->constrained('education')->nullOnDelete();
            $table->foreignId('occupation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nip')->nullable()->unique();
            $table->string('name');
            $table->string('nik', 16)->unique();
            $table->string('nkk', 16)->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('teachers'); }
};
