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
        Schema::create('pest_disease_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_growth_id')->constrained('crop_growth_stages')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->enum('case_type', ['pest', 'disease']);
            $table->json('case_name');
            $table->enum('severity', ['high', 'medium', 'low']);
            $table->json('description');
            $table->json('description');
            $table->date('discovery_date');
            $table->json('location_details');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pest_disease_cases');
    }
};
