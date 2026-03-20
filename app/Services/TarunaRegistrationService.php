<?php

namespace App\Services;

use App\Models\TrbRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TarunaRegistrationService
{
    public function createRegistration(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $rawToken = Str::random(64);

            $registration = TrbRegistration::create([
                'kode_pelaut' => $data['kode_pelaut'],
                'nama' => $data['nama'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'nik_ktp' => $data['nik_ktp'],
                'alamat' => $data['alamat'],
                'kelurahan_desa' => $data['kelurahan_desa'],
                'kecamatan' => $data['kecamatan'],
                'kabupaten_kota' => $data['kabupaten_kota'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_ibu_kandung' => $data['nama_ibu_kandung'],
                'no_whatsapp' => $data['no_whatsapp'],
                'tahun_masuk_taruna' => $data['tahun_masuk_taruna'],

                'registration_year' => now()->year,
                'status' => 'submitted',
                'submitted_at' => now(),

                'edit_token_hash' => Hash::make($rawToken),
                'edit_token_expires_at' => now()->addDays(7),

                // regenerasi oleh admin, jadi biarkan null saat awal
                'token_last_regenerated_by' => null,
                'token_last_regenerated_at' => null,
            ]);

            return [$registration, $rawToken];
        });
    }

    public function verifyEditAccess(string $kodePelaut, string $token): TrbRegistration
    {
        $registration = TrbRegistration::where('kode_pelaut', $kodePelaut)->first();

        if (! $registration) {
            throw ValidationException::withMessages([
                'kode_pelaut' => 'Kode pelaut tidak ditemukan.',
            ]);
        }

        if ($registration->edit_token_expires_at && now()->greaterThan($registration->edit_token_expires_at)) {
            throw ValidationException::withMessages([
                'token' => 'Token sudah kedaluwarsa. Silakan minta admin untuk regenerasi token.',
            ]);
        }

        if (! Hash::check($token, (string) $registration->edit_token_hash)) {
            throw ValidationException::withMessages([
                'token' => 'Token tidak valid.',
            ]);
        }

        return $registration;
    }

    public function updateIdentity(TrbRegistration $registration, array $data): TrbRegistration
    {
        $registration->update([
            'kode_pelaut' => $data['kode_pelaut'],
            'nama' => $data['nama'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'nik_ktp' => $data['nik_ktp'],
            'alamat' => $data['alamat'],
            'kelurahan_desa' => $data['kelurahan_desa'],
            'kecamatan' => $data['kecamatan'],
            'kabupaten_kota' => $data['kabupaten_kota'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'nama_ibu_kandung' => $data['nama_ibu_kandung'],
            'no_whatsapp' => $data['no_whatsapp'],
            'tahun_masuk_taruna' => $data['tahun_masuk_taruna'],
        ]);

        return $registration;
    }

    public function regenerateEditToken(TrbRegistration $registration, int $adminId): string
    {
        return DB::transaction(function () use ($registration, $adminId) {

            // Generate token baru
            $rawToken = Str::random(64);

            $registration->update([
                'edit_token_hash' => Hash::make($rawToken),
                'edit_token_expires_at' => now()->addDays(7),

                'token_last_regenerated_by' => $adminId,
                'token_last_regenerated_at' => now(),
            ]);

            return $rawToken;
        });
    }
}
