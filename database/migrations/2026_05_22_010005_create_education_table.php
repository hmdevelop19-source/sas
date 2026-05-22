<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // SMA, S1, S2
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('education'); }
};
