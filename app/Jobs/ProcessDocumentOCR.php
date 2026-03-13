<?php

namespace App\Jobs;

use App\Jobs\UploadToGoogleDrive;
use App\Models\TrbDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessDocumentOCR implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected TrbDocument $document) {}

    public function handle(): void
    {
        set_time_limit(300);
        $type = $this->document->documentType;

        if (!$type || !$type->ocr_enabled) {
            return;
        }

        $tempImagePath = null;

        try {
            $pdfPath = Storage::disk('public')->path($this->document->drive_file_id);
            $tempImagePath = storage_path('app/temp_ocr_' . $this->document->id . '.png');

            if (!file_exists($pdfPath)) {
                throw new \Exception("File PDF tidak ditemukan di storage.");
            }

            // 1. Konversi PDF ke Gambar
            $imagick = new \Imagick();
            $imagick->setResolution(200, 200);
            $imagick->readImage($pdfPath . '[0]');
            $imagick->setImageFormat('png');
            $imagick->writeImage($tempImagePath);
            $imagick->clear();
            

            // 2. Eksekusi Tesseract
            $tesseractExec = '"C:\Program Files\Tesseract-OCR\tesseract.exe"';
            $ocrText = shell_exec("$tesseractExec \"$tempImagePath\" stdout -l ind+eng --psm 3");

            if (empty($ocrText)) {
                throw new \Exception("Tesseract gagal mengekstrak teks.");
            }

            // 3. Skor Confidence
            $hocrData = shell_exec("$tesseractExec \"$tempImagePath\" stdout -l ind+eng hocr");
            preg_match_all('/x_wconf\s+(\d+)/', $hocrData, $matches);
            $actualConfidence = !empty($matches[1]) ? array_sum($matches[1]) / count($matches[1]) : 0;

            // 4. Keyword Matching
            $keywords = $type->ocr_keywords ?? [];
            $foundKeywords = [];
            $upperText = strtoupper($ocrText);

            foreach ($keywords as $word) {
                if (!empty($word) && str_contains($upperText, strtoupper(trim($word)))) {
                    $foundKeywords[] = trim($word);
                }
            }

            $isMatch = count($foundKeywords) === count($keywords);
            $minConfidence = (float) ($type->ocr_min_confidence ?? 70);
            $isConfident = $actualConfidence >= $minConfidence;

            // 5. Update Database Hasil OCR
            $this->document->update([
                'ocr_text_excerpt' => mb_substr($ocrText, 0, 1500),
                'ocr_status' => 'processed',
                'ocr_confidence' => round($actualConfidence, 2),
                'system_validation_status' => ($isMatch && $isConfident) ? 'passed' : 'failed',
                'system_validation_message' => ($isMatch && $isConfident)
                    ? 'Validasi Otomatis Berhasil.'
                    : ($actualConfidence < $minConfidence
                        ? "Hasil Scan Kabur (" . round($actualConfidence, 0) . "%). Upload ulang."
                        : 'Keyword tidak lengkap.'),
            ]);

            // --- TRIGGER JOB UPLOAD TERPISAH ---
            if ($isMatch && $isConfident && $type->google_drive_folder) {
                Log::info("OCR Sukses. Memasukkan antrean upload untuk: " . $this->document->original_filename);

                $folderId = trim($type->google_drive_folder);

                UploadToGoogleDrive::dispatch($this->document, $folderId);

                Log::info('Dispatch upload ke Drive', [
                    'document_id' => $this->document->id,
                    'filename' => $this->document->original_filename,
                    'folder_id' => $folderId
                ]);
            }
        } catch (\Exception $e) {
            Log::error("OCR Error [Doc ID: {$this->document->id}]: " . $e->getMessage());
            $this->document->update(['ocr_status' => 'failed']);
        } finally {
            if ($tempImagePath && file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
        }
    }
}
