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
        Schema::table('master_document_types', function (Blueprint $table) {
            $table->renameColumn('google_drive_folder_id', 'google_drive_folder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_document_types', function (Blueprint $table) {
            $table->renameColumn('google_drive_folder', 'google_drive_folder_id');
        });
    }
};
