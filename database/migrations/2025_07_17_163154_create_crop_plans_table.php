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
        Schema::create('crop_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained('crops')->cascadeOnDelete();
            $table->foreignId('planned_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
            $table->date('planned_planting_date');
            $table->date('actual_planting_date')->nullable();
            $table->date('planned_harvest_date');
            $table->date('actual_harvest_date')->nullable();
            $table->string('seed_type', 50)->nullable();
            $table->decimal('seed_quantity', 10, 2);
            $table->date('seed_expiry_date')->nullable();
            $table->decimal('area_size', 10, 2);
            $table->enum('status', ['active', 'in-progress', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_plans');
    }
};
