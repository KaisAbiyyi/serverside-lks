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
        Schema::create('spot_vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vaccine_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_vaccines');
    }
};
