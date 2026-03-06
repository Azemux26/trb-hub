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
        Schema::create('trb_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trb_registration_id')->constrained('trb_registrations')->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('master_document_types')->restrictOnDelete();

            $table->unique(['trb_registration_id', 'document_type_id']);

            $table->string('original_filename', 255);
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('size_bytes');
            $table->char('checksum_sha256', 64)->nullable();

            $table->string('drive_file_id', 150)->index();
            $table->text('drive_view_url');
            $table->text('drive_download_url')->nullable();

            $table->string('system_validation_status', 20)->default('pending');
            $table->text('system_validation_message')->nullable();

            $table->string('ocr_status', 20)->default('pending');
            $table->decimal('ocr_confidence', 5, 2)->nullable();
            $table->text('ocr_text_excerpt')->nullable();

            $table->string('admin_verification_status', 20)->default('pending');
            $table->text('admin_verification_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trb_documents');
    }
};
