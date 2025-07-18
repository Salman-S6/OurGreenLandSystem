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
        Schema::create('attachments', function (Blueprint $table) {
             $table->id();
             $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
             $table->text('path');
             $table->text('url')->nullable();
             $table->string('disk');
             $table->nullableMorphs('attachable');
             $table->string('file_name');
             $table->bigInteger('file_size');
             $table->string('mime_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
