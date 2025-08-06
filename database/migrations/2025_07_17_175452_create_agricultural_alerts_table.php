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
        Schema::create('agricultural_alerts', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('message')->nullable();
            $table->foreignId('crop_plan_id')->constrained("crop_plans")->cascadeOnDelete();
            $table->enum('alert_level', ['info', 'warning']);
            $table->enum('alert_type', ['weather', 'general', 'fertilization', 'pest', 'irrigation']);
            $table->timestamp('send_time');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_alerts');
    }
};
