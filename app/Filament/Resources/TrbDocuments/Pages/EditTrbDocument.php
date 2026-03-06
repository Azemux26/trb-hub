<?php

namespace App\Filament\Resources\TrbDocuments\Pages;

use App\Filament\Resources\TrbDocuments\TrbDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrbDocument extends EditRecord
{
    protected static string $resource = TrbDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
