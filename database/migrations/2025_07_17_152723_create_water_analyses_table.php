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
        Schema::create('water_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
            $table->foreignId('performed_by')->constrained('users')->cascadeOnDelete();
            $table->date('sample_date');
            $table->decimal('ph_level', 5, 2);
            $table->decimal('salinity_level', 5, 2);
            $table->string('water_quality')->nullable();
            $table->enum('suitability', ['suitable', 'limited', 'unsuitable']);
            $table->json('contaminants')->nullable();
            $table->json('recommendations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_analyses');
    }
};
