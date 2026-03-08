<?php

namespace App\Jobs;

use App\Models\TrbDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDocumentOCR implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected TrbDocument $document) {}

    public function handle(): void
    {
        $type = $this->document->documentType;

        if (!$type || !$type->ocr_enabled) {
            return;
        }

        $tempImagePath = null;

        try {
            $pdfPath = storage_path('app/public/' . $this->document->drive_file_id);
            $tempImagePath = storage_path('app/temp_ocr_' . $this->document->id . '.png');

            if (!file_exists($pdfPath)) {
                throw new \Exception("File PDF tidak ditemukan di storage.");
            }

            // 1. Konversi PDF ke Gambar menggunakan Imagick
            $imagick = new \Imagick();
            $imagick->setResolution(200, 200); 
            $imagick->readImage($pdfPath . '[0]');
            $imagick->setImageFormat('png');
            $imagick->writeImage($tempImagePath);
            $imagick->clear();

            // 2. Konfigurasi Path Tesseract
            $tesseractExec = '"C:\Program Files\Tesseract-OCR\tesseract.exe"';
            
            // 3. Eksekusi OCR untuk mendapatkan TEKS (stdout)
            // Menggunakan stdout agar tidak menulis file temporary di folder AppData Windows
            $cmdText = "$tesseractExec \"$tempImagePath\" stdout -l ind+eng --psm 3";
            $ocrText = shell_exec($cmdText);

            if (empty($ocrText)) {
                throw new \Exception("Tesseract gagal mengekstrak teks.");
            }

            // 4. Eksekusi OCR untuk mendapatkan SKOR CONFIDENCE (hOCR stdout)
            $cmdHocr = "$tesseractExec \"$tempImagePath\" stdout -l ind+eng hocr";
            $hocrData = shell_exec($cmdHocr);
            
            // Regex untuk mengambil nilai x_wconf (confidence per kata)
            preg_match_all('/x_wconf\s+(\d+)/', $hocrData, $matches);

            if (!empty($matches[1])) {
                $confidences = array_map('floatval', $matches[1]);
                $actualConfidence = array_sum($confidences) / count($confidences);
            } else {
                $actualConfidence = 0;
            }

            // 5. Logika Pencocokan Keyword
            $keywords = $type->ocr_keywords ?? [];
            $foundKeywords = [];
            $upperText = strtoupper($ocrText);

            foreach ($keywords as $word) {
                $cleanWord = trim($word);
                if (!empty($cleanWord) && str_contains($upperText, strtoupper($cleanWord))) {
                    $foundKeywords[] = $cleanWord;
                }
            }

            $isMatch = count($foundKeywords) === count($keywords);
            $minConfidence = (float) ($type->ocr_min_confidence ?? 70);
            $isConfident = $actualConfidence >= $minConfidence;

            // 6. Update Database
            $this->document->update([
                'ocr_text_excerpt' => mb_substr($ocrText, 0, 1500),
                'ocr_status' => 'processed',
                'ocr_confidence' => round($actualConfidence, 2), 
                'system_validation_status' => ($isMatch && $isConfident) ? 'passed' : 'failed',
                'system_validation_message' => ($isMatch && $isConfident)
                    ? 'Validasi Otomatis Berhasil.'
                    : ($actualConfidence < $minConfidence 
                        ? "Hasil Scan Kabur (Skor Asli: " . round($actualConfidence, 0) . "%). Harap upload ulang."
                        : 'Peringatan: Keyword tidak lengkap. Ditemukan: ' . (empty($foundKeywords) ? 'Tidak ada' : implode(', ', $foundKeywords))),
            ]);

        } catch (\Exception $e) {
            Log::error("OCR Error [Doc ID: {$this->document->id}]: " . $e->getMessage());
            
            $this->document->update([
                'ocr_status' => 'failed',
                'system_validation_status' => 'failed',
                'ocr_confidence' => 0,
                'system_validation_message' => 'Gagal OCR: ' . $e->getMessage(),
            ]);

        } finally {
            // Cleanup: Hapus file gambar temporary
            if ($tempImagePath && file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
        }
    }
}