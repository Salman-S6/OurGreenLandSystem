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
        Schema::create('input_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->enum('input_type', ['seeds', 'fertilizers', 'equipment']);
            $table->json('description');
            $table->decimal('quantity', 10, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'in-progress', 'delivered', 'received'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->json('notes')->nullable();
            $table->foreignId('selected_supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_requests');
    }
};
