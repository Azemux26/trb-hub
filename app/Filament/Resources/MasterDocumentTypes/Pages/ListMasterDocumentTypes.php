<?php

namespace App\Filament\Resources\MasterDocumentTypes\Pages;

use App\Filament\Resources\MasterDocumentTypes\MasterDocumentTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMasterDocumentTypes extends ListRecords
{
    protected static string $resource = MasterDocumentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
