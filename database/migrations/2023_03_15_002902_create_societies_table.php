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
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->char('id_card_number');
            $table->string('password');
            $table->string('name');
            $table->dateTime('born_date');
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->foreignId('regional_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societies');
    }
};
