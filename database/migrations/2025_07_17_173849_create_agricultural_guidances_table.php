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
        Schema::create('agricultural_guidances', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('summary');
            $table->string('category', 50);
            $table->enum('language', ['arabic', 'english']);
            $table->foreignId('added_by_id')->constrained('users')->cascadeOnDelete();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_guidances');
    }
};
