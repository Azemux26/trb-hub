<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;

class LaporanController extends Controller
{
    public function __construct(
        private LaporanService $laporanService
    ) {}

    public function downloadPdf()
    {
        return $this->laporanService->generateLaporanPdf();
    }
}