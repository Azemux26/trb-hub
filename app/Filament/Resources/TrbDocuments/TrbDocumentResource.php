<?php

namespace App\Filament\Resources\TrbDocuments;

use App\Filament\Resources\TrbDocuments\Pages\CreateTrbDocument;
use App\Filament\Resources\TrbDocuments\Pages\EditTrbDocument;
use App\Filament\Resources\TrbDocuments\Pages\ListTrbDocuments;
use App\Filament\Resources\TrbDocuments\Schemas\TrbDocumentForm;
use App\Filament\Resources\TrbDocuments\Tables\TrbDocumentsTable;
use App\Models\TrbDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrbDocumentResource extends Resource
{
    protected static ?string $model = TrbDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TrbDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrbDocumentsTable::configure($table);
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
            'index' => ListTrbDocuments::route('/'),
            'create' => CreateTrbDocument::route('/create'),
            'edit' => EditTrbDocument::route('/{record}/edit'),
        ];
    }
}
