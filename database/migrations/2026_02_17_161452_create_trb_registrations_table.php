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
        Schema::create('trb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelaut', 50)->index();
            $table->string('nama', 150);
            $table->string('tempat_lahir', 120);
            $table->date('tanggal_lahir');

            $table->string('nik_ktp', 30)->index();
            $table->text('alamat');
            $table->string('kelurahan_desa', 120);
            $table->string('kecamatan', 120);
            $table->string('kabupaten_kota', 120);
            $table->string('jenis_kelamin', 20);

            $table->string('nama_ibu_kandung', 150);
            $table->string('no_whatsapp', 30);
            $table->unsignedSmallInteger('tahun_masuk_taruna');

            $table->unsignedSmallInteger('registration_year')->nullable();

            $table->string('status', 30)->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->string('edit_token_hash', 255);
            $table->timestamp('edit_token_expires_at');

            $table->foreignId('token_last_regenerated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('token_last_regenerated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trb_registrations');
    }
};
