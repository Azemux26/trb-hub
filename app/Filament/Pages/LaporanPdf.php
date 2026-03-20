<?php

namespace App\Filament\Pages;

use App\Models\TrbDocument;
use App\Models\TrbRegistration;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class LaporanPdf extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static ?string $navigationLabel = 'Laporan PDF';

    protected static ?string $title = 'Export Laporan PDF';

    protected static ?int $navigationSort = 10;

    // Data untuk ditampilkan di view
    public int $totalTaruna    = 0;
    public int $totalDokumen   = 0;
    public int $totalApproved  = 0;
    public int $totalPending   = 0;
    public int $totalRejected  = 0;

    public function mount(): void
    {
        $this->totalTaruna   = TrbRegistration::count();
        $this->totalDokumen  = TrbDocument::count();
        $this->totalApproved = TrbDocument::where('admin_verification_status', 'approved')->count();
        $this->totalPending  = TrbDocument::where('admin_verification_status', 'pending')->count();
        $this->totalRejected = TrbDocument::where('admin_verification_status', 'rejected')->count();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download PDF Sekarang')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(route('admin.laporan.pdf'))
                ->openUrlInNewTab(),
        ];
    }
}