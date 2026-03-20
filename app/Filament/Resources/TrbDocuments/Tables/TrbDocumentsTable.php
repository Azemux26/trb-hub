<?php

namespace App\Filament\Resources\TrbDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class TrbDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration.nama')
                    ->label('Nama Taruna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('documentType.name')
                    ->label('Jenis Dokumen')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('original_filename')
                    ->label('Nama File')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->original_filename),

                TextColumn::make('size_bytes')
                    ->label('Ukuran')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 0) . ' KB')
                    ->sortable(),

                // ── Kolom Preview — cerdas deteksi local vs Drive
                TextColumn::make('drive_file_id')
                    ->label('Lihat Dokumen')
                    ->badge()
                    ->icon('heroicon-m-eye')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return 'Belum Ada';

                        // Cek apakah masih local (ada slash) atau sudah di Drive
                        $isLocal = str_contains($state, '/');
                        return $isLocal ? 'Lihat (Lokal)' : 'Lihat (Drive)';
                    })
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        $isLocal = str_contains($state, '/');
                        return $isLocal ? 'warning' : 'success';
                    })
                    ->url(function ($state, $record = null) {
                        $driveFileId = $state ?? ($record?->drive_file_id ?? null);
                        $driveViewUrl = $record?->drive_view_url ?? null;

                        if (!$driveFileId) {
                            return null;
                        }

                        $isLocal = str_contains($driveFileId, '/');

                        if ($isLocal) {
                            // Generate URL local storage (avoid static analyzer issue on FilesystemAdapter url method)
                            $baseUrl = config('filesystems.disks.public.url');

                            return $baseUrl
                                ? rtrim($baseUrl, '/') . '/' . ltrim($driveFileId, '/')
                                : asset('storage/' . ltrim($driveFileId, '/'));
                        }

                        // Sudah di Drive — pakai drive_view_url
                        return $driveViewUrl;
                    })
                    ->openUrlInNewTab(),

                // Badge: Status Validasi Sistem
                TextColumn::make('system_validation_status')
                    ->label('Validasi Sistem')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'passed'  => '✓ Lolos',
                        'failed'  => '✗ Gagal',
                        default   => '⏳ Menunggu',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'passed'  => 'success',
                        'failed'  => 'danger',
                        default   => 'gray',
                    })
                    ->searchable(),

                // Badge: Status OCR
                TextColumn::make('ocr_status')
                    ->label('Status OCR')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'processed' => '✓ Selesai',
                        'failed'    => '✗ Error',
                        default     => '⏳ Antrian',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'processed' => 'success',
                        'failed'    => 'danger',
                        default     => 'warning',
                    }),

                // Badge: Skor OCR 3 kondisi
                TextColumn::make('ocr_confidence')
                    ->label('Skor OCR')
                    ->suffix('%')
                    ->numeric(decimalPlaces: 1)
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 70    => 'success',
                        $state >= 50    => 'warning',
                        default         => 'danger',
                    }),

                // Badge: Verifikasi Admin
                TextColumn::make('admin_verification_status')
                    ->label('Verifikasi Admin')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => '✓ Disetujui',
                        'rejected' => '✗ Ditolak',
                        default    => '⏳ Menunggu',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('verifiedBy.name')
                    ->label('Diverifikasi Oleh')
                    ->default('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('verified_at')
                    ->label('Waktu Verifikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('uploaded_at')
                    ->label('Waktu Upload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('uploaded_at', 'desc')
            ->filters([
                SelectFilter::make('system_validation_status')
                    ->label('Validasi Sistem')
                    ->options([
                        'pending' => '⏳ Menunggu',
                        'passed'  => '✓ Lolos',
                        'failed'  => '✗ Gagal',
                    ]),

                SelectFilter::make('admin_verification_status')
                    ->label('Verifikasi Admin')
                    ->options([
                        'pending'  => '⏳ Menunggu',
                        'approved' => '✓ Disetujui',
                        'rejected' => '✗ Ditolak',
                    ]),

                SelectFilter::make('ocr_status')
                    ->label('Status OCR')
                    ->options([
                        'pending'   => '⏳ Antrian',
                        'processed' => '✓ Selesai',
                        'failed'    => '✗ Error',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Verifikasi')
                    ->icon('heroicon-m-check-badge')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}