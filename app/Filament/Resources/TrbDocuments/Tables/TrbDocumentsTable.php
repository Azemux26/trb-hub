<?php

namespace App\Filament\Resources\TrbDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrbDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trb_registration_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('documentType.name')
                    ->searchable(),
                TextColumn::make('original_filename')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->searchable(),
                TextColumn::make('size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('checksum_sha256')
                    ->searchable(),
                TextColumn::make('drive_file_id')
                    ->searchable(),
                TextColumn::make('system_validation_status')
                    ->searchable(),
                TextColumn::make('ocr_status')
                    ->searchable(),
                TextColumn::make('ocr_confidence')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('admin_verification_status')
                    ->searchable(),
                TextColumn::make('verified_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('uploaded_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
