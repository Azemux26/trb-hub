<?php

namespace App\Filament\Resources\TrbRegistrations\Pages;

use App\Filament\Resources\TrbRegistrations\TrbRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrbRegistrations extends ListRecords
{
    protected static string $resource = TrbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
