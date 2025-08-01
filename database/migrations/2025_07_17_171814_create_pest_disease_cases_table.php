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
            $table->foreignId('crop_plan_id')->constrained('crop_plans')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->enum('case_type', ['pest', 'disease']);
            $table->string('case_name', 50);
            $table->enum('severity', ['high', 'medium', 'low']);
            $table->text('description');
            $table->date('discovery_date');
            $table->text('location_details');
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
