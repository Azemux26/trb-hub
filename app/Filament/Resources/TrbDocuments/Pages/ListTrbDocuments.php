<?php

namespace App\Filament\Resources\TrbDocuments\Pages;

use App\Filament\Resources\TrbDocuments\TrbDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrbDocuments extends ListRecords
{
    protected static string $resource = TrbDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
