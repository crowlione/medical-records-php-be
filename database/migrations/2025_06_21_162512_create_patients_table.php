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
        Schema::create('patients', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('egn')->unique();
            $table->boolean('has_insurance')->default(true)->comment('Indicates if the patient has health insurance (last 6 months)');
            $table->foreignId('gp_id')
                ->nullable()
                ->constrained('doctors')
                ->onDelete('set null')
                ->comment('General Practitioner (GP) assigned to the patient');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
