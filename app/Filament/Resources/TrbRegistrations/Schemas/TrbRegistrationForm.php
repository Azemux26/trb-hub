<?php

namespace App\Filament\Resources\TrbRegistrations\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class TrbRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pendaftaran')
                ->schema([
                    TextInput::make('kode_pelaut')
                        ->label('Kode Pelaut')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('nama')
                        ->label('Nama')
                        ->required()
                        ->maxLength(150),
                    TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->required()
                        ->maxLength(100),
                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required(),
                    TextInput::make('nik_ktp')
                        ->label('NIK KTP')
                        ->required()
                        ->maxLength(16),
                    Textarea::make('alamat')
                        ->label('Alamat')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('kelurahan_desa')
                        ->label('Kelurahan/Desa')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('kecamatan')
                        ->label('Kecamatan')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('kabupaten_kota')
                        ->label('Kabupaten/Kota')
                        ->required()
                        ->maxLength(100),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'l' => 'Laki-Laki',
                            'p' => 'Perempuan',
                        ])
                        ->required(),
                    TextInput::make('nama_ibu_kandung')
                        ->label('Nama Ibu Kandung')
                        ->required()
                        ->maxLength(150),
                    TextInput::make('no_whatsapp')
                        ->label('Nomor WhatsApp')
                        ->required()
                        ->maxLength(15),
                    TextInput::make('tahun_masuk_taruna')
                        ->label('Tahun Masuk Taruna')
                        ->numeric()
                        ->required(),
                    // TextInput::make('registration_year')
                    //     ->label('Tahun Pendaftaran')
                    //     ->numeric()
                    //     ->required(),
                    // TextInput::make('status')
                    //     ->label('Status Pendaftaran')
                    //     ->required()
                    //     ->default('draft'),
                ])
                // ->columns(2),

            // Section::make('Upload Dokumen')
            //     ->schema([
            //         FileUpload::make('documents')
            //             ->label('Upload Dokumen')
            //             ->multiple()
            //             ->required()
            //             ->disk('public')
            //             ->directory('uploads'),
            //     ])
            //     ->columns(1),
        ]);
    }
}
