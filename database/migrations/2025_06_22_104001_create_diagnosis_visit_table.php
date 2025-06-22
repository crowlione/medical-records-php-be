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
        Schema::create('diagnosis_visit', function (Blueprint $table) {
            $table->foreignId('diagnosis_id')
                ->constrained('diagnoses')
                ->onDelete('cascade');
            $table->foreignId('visit_id')
                ->constrained('visits')
                ->onDelete('cascade');
            $table->primary(['diagnosis_id', 'visit_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_visit');
    }
};
