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
        Schema::create('best_agricultural_practices', function (Blueprint $table) {
             $table->id();
             $table->foreignId('growth_stage_id')->constrained('crop_growth_stages')->cascadeOnDelete();
             $table->foreignId('expert_id')->constrained('users')->cascadeOnDelete();
             $table->enum('practice_type', ['irrigation', 'fertilization', 'pest-control']);
             $table->string('material');
             $table->decimal('quantity', 10, 2);
             $table->date('application_date');
             $table->text('notes')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_agricultural_practices');
    }
};
