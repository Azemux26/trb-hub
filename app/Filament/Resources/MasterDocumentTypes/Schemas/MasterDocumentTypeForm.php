<?php

namespace App\Filament\Resources\MasterDocumentTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MasterDocumentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas Dokumen')
                ->schema([
                    TextInput::make('code')
                        ->label('Code')
                        ->helperText('Huruf kecil dan underscore. Contoh: ktp, buku_pelaut')
                        ->required()
                        ->maxLength(80)
                        ->regex('/^[a-z0-9_]+$/')
                        ->unique(ignoreRecord: true)
                        ->disabled(fn ($record) => filled($record))
                        ->dehydrated(),

                    TextInput::make('name')
                        ->label('Nama Dokumen')
                        ->required()
                        ->maxLength(150),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(2),

            Section::make('Aturan Upload')
                ->schema([
                    Toggle::make('is_required')
                        ->label('Wajib')
                        ->default(true),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    TextInput::make('max_size_mb')
                        ->label('Maksimal Ukuran (MB)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(50)
                        ->default(10)
                        ->required(),

                    Select::make('allowed_mime_types')
                        ->label('Tipe File Diizinkan')
                        ->multiple()
                        ->required()
                        ->options([
                            'application/pdf' => 'PDF',
                            'image/jpeg' => 'JPG',
                            'image/png' => 'PNG',
                        ])
                        ->helperText('Pilih format file yang diperbolehkan.'),
                ])
                ->columns(2),

            Section::make('Aturan OCR dan Validasi Konten')
                ->schema([
                    Toggle::make('ocr_enabled')
                        ->label('OCR Aktif')
                        ->default(true),

                    TextInput::make('ocr_min_confidence')
                        ->label('Minimal Confidence OCR')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(70.00)
                        ->nullable(),

                    TagsInput::make('ocr_keywords')
                        ->label('Kata Kunci OCR')
                        ->helperText('Isi kata kunci yang harus terdeteksi oleh OCR.')
                        ->nullable()
                        // ->separator(',')
                        ->splitKeys(['Tab','Enter'])
                        ->reorderable(),
                ])
                ->columns(2),

            Section::make('Tampilan')
                ->schema([
                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                ]),
        ]);
    }
}
