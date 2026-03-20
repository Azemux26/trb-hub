<?php

namespace App\Filament\Resources\TrbDocuments\Pages;

use App\Filament\Resources\TrbDocuments\TrbDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EditTrbDocument extends EditRecord
{
    protected static string $resource = TrbDocumentResource::class;

    public function getTitle(): string
    {
        return 'Verifikasi Dokumen Taruna';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Validasi: catatan wajib jika ditolak
        if (
            $data['admin_verification_status'] === 'rejected' &&
            empty(trim($data['admin_verification_note'] ?? ''))
        ) {
            throw ValidationException::withMessages([
                'admin_verification_note' => 'Catatan wajib diisi jika dokumen ditolak.',
            ]);
        }

        // Auto-isi verified_by & verified_at jika bukan pending
        if ($data['admin_verification_status'] !== 'pending') {
            $data['verified_by'] = Auth::user()?->getAuthIdentifier();
            $data['verified_at'] = now()->toDateTimeString();
        } else {
            // Reset jika dikembalikan ke pending
            $data['verified_by'] = null;
            $data['verified_at'] = null;
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        $status = $this->record->admin_verification_status;

        return match($status) {
            'approved' => '✓ Dokumen berhasil disetujui',
            'rejected' => '✗ Dokumen berhasil ditolak',
            default    => 'Status verifikasi diperbarui',
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}