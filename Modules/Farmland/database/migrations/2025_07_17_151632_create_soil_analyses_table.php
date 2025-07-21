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
        Schema::create('soil_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
            $table->foreignId('performed_by')->constrained('users')->cascadeOnDelete();
            $table->date('sample_date');
            $table->decimal('ph_level', 5, 2);
            $table->decimal('salinity_level', 5, 2);
            $table->enum('fertility_level', ['high', 'medium', 'low']);
            $table->text('nutrient_content')->nullable();
            $table->text('contaminants')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soil_analyses');
    }
};
