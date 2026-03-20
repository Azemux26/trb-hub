<?php

namespace App\Services;

use App\Models\MasterDocumentType;
use App\Models\TrbRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class LaporanService
{
    /**
     * Generate dan return PDF laporan semua taruna.
     */
    public function generateLaporanPdf(): Response
    {
        $registrations = TrbRegistration::with([
            'documents.documentType',
        ])
        ->orderBy('nama')
        ->get();

        $documentTypes = MasterDocumentType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $pdf = Pdf::loadView('pdf.laporan-taruna', [
            'registrations' => $registrations,
            'documentTypes' => $documentTypes,
            'generatedAt'   => now()->format('d M Y, H:i'),
        ])
        ->setPaper('a4', 'landscape')
        ->setOptions([
            'defaultFont'          => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => false,
        ]);

        $filename = 'Laporan-Dokumen-Taruna-TRB-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}