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
        Schema::create('rehabilitation_land', function (Blueprint $table) {
            $table->id();
            $table->foreignId("land_id")->constrained("lands")->cascadeOnDelete();
            $table->foreignId("rehabilitation_id")->constrained("rehabilitations")->cascadeOnDelete();
            $table->foreignId("performed_by")->constrained("users")->cascadeOnDelete();
            $table->date('performed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehabilitation_land');
    }
};
