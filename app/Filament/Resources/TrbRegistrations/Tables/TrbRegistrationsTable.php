<?php

namespace App\Filament\Resources\TrbRegistrations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Services\TarunaRegistrationService;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class TrbRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pelaut')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('nik_ktp')
                    ->searchable(),
                TextColumn::make('kelurahan_desa')
                    ->searchable(),
                TextColumn::make('kecamatan')
                    ->searchable(),
                TextColumn::make('kabupaten_kota')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->searchable(),
                TextColumn::make('nama_ibu_kandung')
                    ->searchable(),
                TextColumn::make('no_whatsapp')
                    ->searchable(),
                TextColumn::make('tahun_masuk_taruna')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('registration_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'submitted' => 'Terdaftar',
                        'verified'  => 'Terverifikasi',
                        default     => 'Draft',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'submitted' => 'warning',
                        'verified'  => 'success',
                        default     => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('edit_token_expires_at')
                    ->label('Token Berlaku Hingga')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state && now()->greaterThan($state) ? 'danger' : 'success'),
                TextColumn::make('token_last_regenerated_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('token_last_regenerated_at')
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

                Action::make('regenerateToken')
                    ->label('Regenerate Token')
                    ->icon('heroicon-o-arrow-path')
                    ->color('Indigo')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate Token Taruna')
                    ->modalDescription('Token lama akan menjadi tidak berlaku.')
                    ->action(function ($record) {

                        $service = app(TarunaRegistrationService::class);

                        $newToken = $service->regenerateEditToken(
                            $record,
                            Auth::id()
                        );

                        Notification::make()
                            ->title('Token baru berhasil dibuat')
                            ->body("Token baru: {$newToken}")
                            ->success()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
