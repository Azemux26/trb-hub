<?php

namespace App\Filament\Resources\TrbDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters;
use Filament\Tables\Filters\SelectFilter;

class TrbDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trb_registration_id')
                    ->label('ID Reg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('documentType.name')
                    ->label('Jenis Dokumen')
                    ->searchable(),
                TextColumn::make('original_filename')
                    ->label('Nama File yang Diupload Taruna/I')
                    ->searchable(),
                // TextColumn::make('mime_type')
                //     ->searchable(),
                TextColumn::make('size_bytes')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('checksum_sha256')
                //     ->searchable(),
                TextColumn::make('drive_file_id')
                    ->label('Link Google Drive (nanti)')
                    ->searchable(),
                TextColumn::make('system_validation_status')
                    ->label('Validasi Sistem')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'passed' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->searchable(),
                TextColumn::make('ocr_status')
                    ->searchable(),
                TextColumn::make('ocr_confidence')
                    ->label('Skor OCR')
                    ->suffix('%')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < 70 ? 'danger' : 'success'),
                TextColumn::make('admin_verification_status')
                    ->label('Status Admin')
                    ->badge()  
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
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
                SelectFilter::make('system_validation_status')
                    ->options([
                        'passed' => 'Lolos Scan',
                        'failed' => 'Gagal Scan',

                    ])
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
