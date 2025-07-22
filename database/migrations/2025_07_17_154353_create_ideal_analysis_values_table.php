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
        Schema::create('ideal_analysis_values', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['soil', 'water']);
            $table->foreignId('crop_id')->constrained('crops')->cascadeOnDelete();
            $table->decimal('ph_min', 5, 2);
            $table->decimal('ph_max', 5, 2);
            $table->decimal('salinity_min', 5, 2);
            $table->decimal('salinity_max', 5, 2);
            $table->enum('fertility_level', ['low', 'medium', 'high'])->nullable();
            $table->string('water_quality', 50)->nullable();
            $table->json('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideal_analysis_values');
    }
};
