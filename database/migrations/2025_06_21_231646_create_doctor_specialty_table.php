<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->onDelete('cascade');
            $table->foreignId('specialty_id')
                ->constrained('specialties')
                ->onDelete('cascade');
            $table->primary(['doctor_id', 'specialty_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_specialty');
    }
};
