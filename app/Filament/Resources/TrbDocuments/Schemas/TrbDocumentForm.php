<?php

namespace App\Filament\Resources\TrbDocuments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TrbDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trb_registration_id')
                    ->required()
                    ->numeric(),
                Select::make('document_type_id')
                    ->relationship('documentType', 'name')
                    ->required(),
                TextInput::make('original_filename')
                    ->required(),
                TextInput::make('mime_type')
                    ->required(),
                TextInput::make('size_bytes')
                    ->required()
                    ->numeric(),
                TextInput::make('checksum_sha256'),
                TextInput::make('drive_file_id')
                    ->required(),
                Textarea::make('drive_view_url')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('drive_download_url')
                    ->columnSpanFull(),
                TextInput::make('system_validation_status')
                    ->required()
                    ->default('pending'),
                Textarea::make('system_validation_message')
                    ->columnSpanFull(),
                TextInput::make('ocr_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('ocr_confidence')
                    ->numeric(),
                Textarea::make('ocr_text_excerpt')
                    ->columnSpanFull(),
                Select::make('admin_verification_status')
                    ->label('Status Verifikasi Admin')
                    ->options([
                        'pending' => 'Pending (Belum Diperiksa)',
                        'approved' => 'Setujui (Dokumen Sah)',
                        'rejected' => 'Tolak (Upload Ulang)',
                    ])
                    ->required()
                    ->native(false) // Membuat tampilan dropdown lebih modern (Filament style)
                    ->selectablePlaceholder(false)
                    ->prefixIcon('heroicon-m-shield-check')
                    ->default('pending'),

                Textarea::make('admin_verification_note')
                    ->label('Catatan/Alasan Verifikasi')
                    ->placeholder('Contoh: Foto KTP terlalu buram, mohon upload ulang.')
                    ->columnSpanFull()
                    ->helperText('Catatan ini akan dilihat oleh Taruna jika dokumen ditolak.'),
                TextInput::make('verified_by')
                    ->numeric(),
                DateTimePicker::make('verified_at'),
                DateTimePicker::make('uploaded_at')
                    ->required(),
            ]);
    }
}
