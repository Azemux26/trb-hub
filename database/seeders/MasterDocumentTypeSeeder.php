<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MasterDocumentType;
use Illuminate\Database\Seeder;

class MasterDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'code' => 'foto_taruna_dinas',
                'name' => 'FOTO TARUNA DINAS LENGKAP',
                'allowed_mime_types' => ['image/jpeg', 'image/png'],
                'ocr_enabled' => false,
            ],
            [
                'code' => 'tht',
                'name' => 'THT',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['THT'],
            ],
            [
                'code' => 'skl_kertas_kerja_praktek',
                'name' => 'SURAT KETERANGAN LULUS KERTAS KERJA PRAKTEK',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['SURAT', 'KETERANGAN', 'LULUS'],
            ],
            [
                'code' => 'surat_dinas_jaga',
                'name' => 'SURAT DINAS JAGA',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['DINAS', 'JAGA'],
            ],
            [
                'code' => 'surat_izin_berlayar',
                'name' => 'SURAT IZIN BERLAYAR',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['IZIN', 'BERLAYAR'],
            ],
            [
                'code' => 'buku_saku_taruna',
                'name' => 'BUKU SAKU TARUNA',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['BUKU', 'SAKU'],
            ],
            [
                'code' => 'sign_on_off',
                'name' => 'SIGN ON & OFF',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['SIGN', 'ON', 'OFF'],
            ],
            [
                'code' => 'buku_pelaut',
                'name' => 'BUKU PELAUT',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['BUKU', 'PELAUT'],
            ],
            [
                'code' => 'masa_layar',
                'name' => 'MASA LAYAR',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['MASA', 'LAYAR'],
            ],
            [
                'code' => 'skl_ukp_pra',
                'name' => 'SKL UKP PRA',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['SKL', 'UKP'],
            ],
            [
                'code' => 'akte_kelahiran',
                'name' => 'AKTE KELAHIRAN',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['AKTA', 'KELAHIRAN'],
            ],
            [
                'code' => 'ktp',
                'name' => 'KTP',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['KTP', 'NIK'],
            ],
            [
                'code' => 'kwitansi_pembayaran_trb',
                'name' => 'KWITANSI PEMBAYARAN TRB',
                'allowed_mime_types' => ['application/pdf'],
                'ocr_enabled' => true,
                'ocr_keywords' => ['KWITANSI', 'TRB'],
            ],
        ];

        foreach ($documents as $doc) {
            MasterDocumentType::create([
                'code' => $doc['code'],
                'name' => $doc['name'],
                'description' => null,
                'is_required' => true,
                'allowed_mime_types' => $doc['allowed_mime_types'],
                'max_size_mb' => 10,
                'ocr_enabled' => $doc['ocr_enabled'],
                'ocr_keywords' => $doc['ocr_keywords'] ?? null,
                'ocr_min_confidence' => 70.00,
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
