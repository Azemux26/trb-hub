<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Storage;

    class TestGoogleDrive extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'app:test-google-drive';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Tes kirim file ke Google Drive Polimarim';

        /**
         * Execute the console command.
         */
        public function handle()
        {
            $this->info('--- Memulai Tes Koneksi Google Drive ---');

            try {
                // Kita coba buat file teks sederhana
                $namaFile = 'tes_koneksi_' . now()->format('Y-m-d_H-i-s') . '.txt';
                $konten = "Halo Muhammad! Ini file tes dari Laravel 12.\nKoneksi Berhasil pada: " . now();

                $this->info("Sedang mencoba upload: {$namaFile}...");

                // 'google' adalah nama disk yang kita buat di config/filesystems.php
                Storage::disk('google')->put($namaFile, $konten);

                $this->info('========================================');
                $this->info('BERHASIL! File sudah terkirim ke Cloud.');
                $this->info('Silakan cek Google Drive kamu sekarang.');
                $this->info('========================================');
            } catch (\Exception $e) {
                $this->error('Waduh, Gagal Konek!');
                $this->error('Pesan Error: ' . $e->getMessage());
                $this->line("\nTips: Cek apakah file JSON di storage/app sudah benar namanya.");
            }
        }
    }
