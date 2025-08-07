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
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained("users")->cascadeOnDelete();
            $table->foreignId("farmer_id")->constrained("users")->cascadeOnDelete();
            $table->decimal("area",10,2)->nullable();
            $table->string("region", 200);
            $table->foreignId("soil_type_id")->constrained("soils")->cascadeOnDelete();
            $table->enum("damage_level", ["low","medium", "high"])->nullable();
            $table->json('gps_coordinates')->nullable();
            $table->json('boundary_coordinates')->nullable();
            $table->date("rehabilitation_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
