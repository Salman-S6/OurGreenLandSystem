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
        Schema::create('production_estimations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_plan_id')->constrained("crop_plans")->cascadeOnDelete();
            $table->decimal('expected_quantity', 10, 2);
            $table->json('estimation_method');
            $table->decimal('actual_quantity', 10, 2)->nullable();
            $table->json('crop_quality')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->json('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_estimations');
    }
};
