<?php

namespace App\Filament\Resources\MasterDocumentTypes;

use App\Filament\Resources\MasterDocumentTypes\Pages\CreateMasterDocumentType;
use App\Filament\Resources\MasterDocumentTypes\Pages\EditMasterDocumentType;
use App\Filament\Resources\MasterDocumentTypes\Pages\ListMasterDocumentTypes;
use App\Filament\Resources\MasterDocumentTypes\Schemas\MasterDocumentTypeForm;
use App\Filament\Resources\MasterDocumentTypes\Tables\MasterDocumentTypesTable;
use App\Models\MasterDocumentType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MasterDocumentTypeResource extends Resource
{
    protected static ?string $model = MasterDocumentType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MasterDocumentTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterDocumentTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMasterDocumentTypes::route('/'),
            'create' => CreateMasterDocumentType::route('/create'),
            'edit' => EditMasterDocumentType::route('/{record}/edit'),
        ];
    }
}
