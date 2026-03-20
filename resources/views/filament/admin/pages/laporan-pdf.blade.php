<x-filament-panels::page>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 
                    bg-white dark:bg-gray-800 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-primary-600">
                {{ $this->totalTaruna }}
            </div>
            <div class="text-sm text-gray-500 mt-1">Total Taruna</div>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 
                    bg-white dark:bg-gray-800 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-gray-600 dark:text-gray-300">
                {{ $this->totalDokumen }}
            </div>
            <div class="text-sm text-gray-500 mt-1">Total Dokumen</div>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 
                    bg-white dark:bg-gray-800 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-success-600">
                {{ $this->totalApproved }}
            </div>
            <div class="text-sm text-gray-500 mt-1">Disetujui</div>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 
                    bg-white dark:bg-gray-800 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-warning-600">
                {{ $this->totalPending }}
            </div>
            <div class="text-sm text-gray-500 mt-1">Menunggu Verifikasi</div>
        </div>

    </div>

    {{-- Info Box --}}
    <div class="rounded-xl border border-primary-200 dark:border-primary-800 
                bg-primary-50 dark:bg-primary-950 p-5 text-sm 
                text-primary-700 dark:text-primary-300">
        <p class="font-semibold mb-2">📄 Informasi Laporan:</p>
        <ul class="space-y-1 list-disc list-inside">
            <li>Format: <strong>PDF Landscape A4</strong></li>
            <li>Berisi: Data identitas taruna + status semua dokumen</li>
            <li>Kolom dokumen menyesuaikan jenis dokumen yang aktif</li>
            <li>Digenerate real-time saat tombol diklik</li>
        </ul>
    </div>

</x-filament-panels::page>