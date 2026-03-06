<?php

namespace App\Services;

use App\Models\MasterDocumentType;
use App\Models\TrbDocument;
use App\Models\TrbRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TarunaDocumentService
{
   public function upsertDocument(TrbRegistration $registration, MasterDocumentType $type, UploadedFile $file): TrbDocument
    {
        return DB::transaction(function () use ($registration, $type, $file) {
            $path = $file->storeAs(
                'documents/' . $registration->id . '/' . $type->code,
                $file->hashName(),
                'public'
            );

            $baseUrl = config('filesystems.disks.public.url');
            $viewUrl = $baseUrl
                ? rtrim($baseUrl, '/') . '/' . ltrim($path, '/')
                : asset('storage/' . ltrim($path, '/'));

            $checksum = hash_file('sha256', $file->getRealPath());

            // Cari doc lama untuk delete file lama kalau local
            $existing = TrbDocument::where('trb_registration_id', $registration->id)
                ->where('document_type_id', $type->id)
                ->first();

            if ($existing && $existing->drive_file_id) {
                // Aman untuk Drive juga, karena exists akan false untuk ID Drive
                $disk = Storage::disk('public');
                if ($disk->exists($existing->drive_file_id)) {
                    $disk->delete($existing->drive_file_id);
                }
            }

            $payload = [
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => (string) $file->getMimeType(),
                'size_bytes' => (int) $file->getSize(),
                'checksum_sha256' => $checksum,

                // sementara local
                'drive_file_id' => $path,
                'drive_view_url' => $viewUrl,
                'drive_download_url' => $viewUrl,

                'system_validation_status' => 'passed',
                'system_validation_message' => 'Validasi tipe dan ukuran file berhasil.',

                'ocr_status' => $type->ocr_enabled ? 'pending' : 'processed',

                'admin_verification_status' => 'pending',
                'uploaded_at' => now(),
            ];

            $doc = TrbDocument::updateOrCreate(
                [
                    'trb_registration_id' => $registration->id,
                    'document_type_id' => $type->id,
                ],
                $payload
            );

            return $doc->fresh();
        });
    }
}
