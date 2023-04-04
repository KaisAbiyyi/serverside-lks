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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pending', 'accepted', 'declined']);
            $table->text('disease_history')->nullable();
            $table->text('current_symptoms')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->foreignId('society_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('doctor_id')->nullable()->constrained('medicals')->where('role', '=', 'doctor')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
