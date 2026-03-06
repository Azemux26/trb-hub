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
        Schema::create('master_document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 80)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();

            $table->boolean('is_required')->default(true);
            $table->json('allowed_mime_types');
            $table->unsignedSmallInteger('max_size_mb')->default(10);

            $table->boolean('ocr_enabled')->default(true);
            $table->json('ocr_keywords')->nullable();
            $table->decimal('ocr_min_confidence', 5, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_document_types');
    }
};
