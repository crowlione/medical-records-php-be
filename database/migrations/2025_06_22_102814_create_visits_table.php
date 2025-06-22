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
        Schema::create('visits', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->onDelete('cascade');
            $table->dateTime('visit_date');
            $table->text('treatment')->nullable()->comment('Details of the treatment provided during the visit');
            $table->foreignId('sick_leave_id')
                ->nullable()
                ->constrained('sick_leaves')
                ->onDelete('set null')
                ->comment('Reference to the sick leave associated with this visit, if any');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
