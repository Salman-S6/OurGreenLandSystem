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
        Schema::create('pest_disease_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pest_disease_case_id')->constrained('pest_disease_cases')->cascadeOnDelete();
            $table->string('recommendation_name');
            $table->string('recommended_dose');
            $table->text('application_method');
            $table->text('safety_notes')->nullable();
            $table->foreignId('recommended_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pest_disease_recommendations');
    }
};
