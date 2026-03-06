<?php

namespace App\Filament\Resources\MasterDocumentTypes\Pages;

use App\Filament\Resources\MasterDocumentTypes\MasterDocumentTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMasterDocumentType extends EditRecord
{
    protected static string $resource = MasterDocumentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
