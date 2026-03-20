<?php

namespace App\Filament\Resources\TrbRegistrations;

use App\Filament\Resources\TrbRegistrations\Pages\CreateTrbRegistration;
use App\Filament\Resources\TrbRegistrations\Pages\EditTrbRegistration;
use App\Filament\Resources\TrbRegistrations\Pages\ListTrbRegistrations;
use App\Filament\Resources\TrbRegistrations\Tables\TrbRegistrationsTable;
use App\Filament\Resources\TrbRegistrations\Schemas\TrbRegistrationForm;
use App\Models\TrbRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrbRegistrationResource extends Resource
{
    protected static ?string $model = TrbRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $modelLabel = 'Pendaftaran Taruna';

    protected static ?string $pluralModelLabel = 'Pendaftaran Taruna';

    protected static ?string $navigationLabel = 'Pendaftaran Taruna';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TrbRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrbRegistrationsTable::configure($table);
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
            'index' => ListTrbRegistrations::route('/'),
            'create' => CreateTrbRegistration::route('/create'),
            'edit' => EditTrbRegistration::route('/{record}/edit'),
        ];
    }
}
