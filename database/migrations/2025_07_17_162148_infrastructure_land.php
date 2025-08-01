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
        Schema::create("infrastructure_land", function (Blueprint $table) {
            $table->id();
            $table->foreignId("land_id")->constrained("lands")->cascadeOnDelete();
            $table->foreignId("infrastructure_id")->constrained("agricultural_infrastructures")->cascadeOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
