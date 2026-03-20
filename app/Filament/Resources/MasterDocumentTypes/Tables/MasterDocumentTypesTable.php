<?php

namespace App\Filament\Resources\MasterDocumentTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MasterDocumentTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->alignCenter(),
                TextColumn::make('max_size_mb')
                    ->label('Maks. Ukuran')
                    ->suffix(' MB')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('ocr_enabled')
                    ->label('OCR')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCpuChip ?? 'heroicon-o-cpu-chip')
                    ->trueColor('info')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),
                TextColumn::make('ocr_min_confidence')
                    ->label('Min. Confidence')
                    ->suffix('%')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state === null     => 'gray',
                        $state >= 70        => 'success',
                        default             => 'warning',
                    }),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->width('50px'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
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
