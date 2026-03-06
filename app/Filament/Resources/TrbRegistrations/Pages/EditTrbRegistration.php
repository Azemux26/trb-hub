<?php

namespace App\Filament\Resources\TrbRegistrations\Pages;

use App\Filament\Resources\TrbRegistrations\TrbRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrbRegistration extends EditRecord
{
    protected static string $resource = TrbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
