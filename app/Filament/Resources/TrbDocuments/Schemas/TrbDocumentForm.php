<?php

namespace App\Filament\Resources\TrbDocuments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class TrbDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ─── SECTION 1: Informasi Dokumen ───────────────────────────
            Section::make('📄 Informasi Dokumen')
                ->description('Data dokumen yang diunggah oleh Taruna. Tidak dapat diubah.')
                ->schema([
                    TextInput::make('registration.nama')
                        ->label('Nama Taruna')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('registration.kode_pelaut')
                        ->label('Kode Pelaut')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('documentType.name')
                        ->label('Jenis Dokumen')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('original_filename')
                        ->label('Nama File')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('mime_type')
                        ->label('Tipe File')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('size_bytes')
                        ->label('Ukuran File')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => $state
                            ? number_format($state / 1024, 0) . ' KB'
                            : '—'
                        ),

                    TextInput::make('uploaded_at')
                        ->label('Waktu Upload')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => $state
                            ? \Carbon\Carbon::parse($state)->format('d M Y, H:i')
                            : '—'
                        ),

                    // Tombol Drive pakai Textarea disabled agar bisa klik via HTML
                    Textarea::make('drive_view_url')
                        ->label('Link Google Drive')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText(fn ($record) => $record?->drive_view_url
                            ? new HtmlString(
                                '<a href="' . $record->drive_view_url . '" 
                                    target="_blank" 
                                    style="display:inline-flex;align-items:center;gap:6px;
                                           background:#1a1a7a;color:#fff;padding:6px 14px;
                                           border-radius:6px;font-size:0.85rem;text-decoration:none;
                                           margin-top:4px;">
                                    🔗 Klik untuk Buka di Google Drive
                                </a>'
                            )
                            : null
                        )
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible(),

            // ─── SECTION 2: Hasil OCR ────────────────────────────────────
            Section::make('🔍 Hasil Analisis OCR')
                ->description('Hasil pembacaan otomatis sistem. Gunakan ini sebagai referensi verifikasi.')
                ->schema([
                    TextInput::make('ocr_status')
                        ->label('Status OCR')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => match($state) {
                            'processed' => '✓ Selesai Diproses',
                            'failed'    => '✗ Gagal Diproses',
                            default     => '⏳ Dalam Antrian',
                        }),

                    TextInput::make('ocr_confidence')
                        ->label('Skor Kepercayaan OCR')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => $state !== null
                            ? number_format($state, 1) . '%'
                            : '—'
                        ),

                    TextInput::make('system_validation_status')
                        ->label('Validasi Sistem')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => match($state) {
                            'passed' => '✓ Lolos Validasi',
                            'failed' => '✗ Gagal Validasi',
                            default  => '⏳ Menunggu',
                        }),

                    TextInput::make('system_validation_message')
                        ->label('Pesan Sistem')
                        ->disabled()
                        ->dehydrated(false),

                    Textarea::make('ocr_text_excerpt')
                        ->label('Cuplikan Teks OCR')
                        ->disabled()
                        ->dehydrated(false)
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible(),

            // ─── SECTION 3: Keputusan Verifikasi Admin ───────────────────
            Section::make('✅ Keputusan Verifikasi')
                ->description('Hanya bagian ini yang dapat diisi oleh Admin.')
                ->schema([
                    Select::make('admin_verification_status')
                        ->label('Keputusan Admin')
                        ->options([
                            'pending'  => '⏳ Belum Diperiksa',
                            'approved' => '✓ Setujui — Dokumen Sah',
                            'rejected' => '✗ Tolak — Minta Upload Ulang',
                        ])
                        ->required()
                        ->native(false)
                        ->selectablePlaceholder(false)
                        ->prefixIcon('heroicon-m-shield-check')
                        ->default('pending')
                        ->columnSpanFull(),

                    Textarea::make('admin_verification_note')
                        ->label('Catatan untuk Taruna')
                        ->placeholder('Contoh: Foto KTP terlalu buram, mohon upload ulang dengan kualitas lebih baik.')
                        ->helperText('⚠️ Wajib diisi jika dokumen ditolak. Catatan ini akan ditampilkan kepada Taruna.')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(1),

        ]);
    }
}