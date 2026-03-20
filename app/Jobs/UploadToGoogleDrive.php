<?php

namespace App\Jobs;

use App\Models\TrbDocument;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadToGoogleDrive implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;

    public function __construct(
        protected TrbDocument $document,
        protected string $folderId
    ) {}

    public function handle(): void
    {
        try {

            $localPath = $this->document->drive_file_id;

            if (!Storage::disk('public')->exists($localPath)) {

                Log::error('File tidak ditemukan di storage', [
                    'path' => $localPath
                ]);

                return;
            }

            Log::info('Memulai upload ke Google Drive', [
                'document_id' => $this->document->id,
                'folder_id' => $this->folderId
            ]);

            $client = new Client();

            $client->setClientId(config('filesystems.disks.google.clientId'));
            $client->setClientSecret(config('filesystems.disks.google.clientSecret'));
            $client->refreshToken(config('filesystems.disks.google.refreshToken'));

            $client->setAccessType('offline');

            $service = new Drive($client);

            $fileName = time() . '_' . $this->document->original_filename;

            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$this->folderId],
            ]);

            $content = Storage::disk('public')->get($localPath);

            $file = $service->files->create(
                $fileMetadata,
                [
                    'data' => $content,
                    'mimeType' => File::mimeType(storage_path('app/public/' . $localPath)),
                    'uploadType' => 'multipart',
                ]
            );

            Log::info('Upload berhasil ke Google Drive', [
                'google_file_id' => $file->id
            ]);

            $fileId = $file->id;

            $viewUrl = "https://drive.google.com/file/d/{$fileId}/view";
            $downloadUrl = "https://drive.google.com/uc?id={$fileId}";

            $this->document->update([
                'drive_file_id' => $fileId,
                'drive_view_url' => $viewUrl,
                'drive_download_url' => $downloadUrl,
            ]);

            Storage::disk('public')->delete($localPath);
        } catch (\Exception $e) {

            Log::error('Upload Drive gagal', [
                'error' => $e->getMessage(),
                'document_id' => $this->document->id
            ]);

            throw $e;
        }
    }
}
