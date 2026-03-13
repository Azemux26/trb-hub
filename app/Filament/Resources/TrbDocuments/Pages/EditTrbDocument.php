<?php

namespace App\Filament\Resources\TrbDocuments\Pages;

use App\Filament\Resources\TrbDocuments\TrbDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditTrbDocument extends EditRecord
{
    protected static string $resource = TrbDocumentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika status diubah menjadi approved atau rejected (bukan lagi pending)
        if ($data['admin_verification_status'] !== 'pending') {
            $data['verified_by'] = Auth::user()?->getAuthIdentifier();
            $data['verified_at'] = now();
        }

        return $data;
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
