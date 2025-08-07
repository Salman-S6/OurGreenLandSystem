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
        Schema::create('input_delivery_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('input_request_id')->constrained('input_requests')->cascadeOnDelete();
            $table->foreignId('action_by')->constrained('users')->cascadeOnDelete();
            $table->enum('action_type', ['pending', 'approved', 'rejected', 'in-progress', 'delivered', 'received']);
            $table->json('rejection_reason')->nullable();
            $table->json('notes')->nullable();
            $table->timestamp('action_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_delivery_statuses');
    }
};
