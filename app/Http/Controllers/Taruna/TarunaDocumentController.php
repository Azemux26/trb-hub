<?php

namespace App\Http\Controllers\Taruna;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taruna\UploadDocumentRequest;
use App\Http\Requests\Taruna\VerifyEditAccessRequest;
use App\Models\MasterDocumentType;
use App\Models\TrbRegistration;
use App\Jobs\ProcessDocumentOCR;
use App\Services\TarunaDocumentService;
use App\Services\TarunaRegistrationService;


class TarunaDocumentController extends Controller
{
    public function __construct(
        private TarunaDocumentService $docService,
        private TarunaRegistrationService $regService,
    ) {}

    private function redirectToAccess()
    {
        return redirect()->route('taruna.docs.access');
    }

    private function getSessionRegistrationId(): ?int
    {
        $id = session('taruna_registration_id');
        return is_numeric($id) ? (int) $id : null;
    }

    public function accessForm()
    {
        return view('taruna-document-access');
    }

    public function accessSubmit(VerifyEditAccessRequest $request)
    {
        $data = $request->validated();

        $registration = $this->regService->verifyEditAccess($data['kode_pelaut'], $data['token']);

        $request->session()->put('taruna_registration_id', $registration->id);
        $request->session()->regenerate();

        return redirect()->route('taruna.docs.index');
    }

    public function index()
    {
        $id = $this->getSessionRegistrationId();
        if (! $id) {
            return $this->redirectToAccess();
        }

        $registration = TrbRegistration::with(['documents.documentType'])->find($id);
        if (! $registration) {
            session()->forget('taruna_registration_id');
            return $this->redirectToAccess();
        }

        $types = MasterDocumentType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $docsByType = $registration->documents->keyBy('document_type_id');

        $requiredTypes = $types->where('is_required', true);
        $requiredTotal = $requiredTypes->count();

        $uploadedRequiredCount = $requiredTypes
            ->filter(fn ($t) => $docsByType->has($t->id))
            ->count();

        return view('taruna-documents', [
            'registration' => $registration,
            'types' => $types,
            'docsByType' => $docsByType,
            'requiredTotal' => $requiredTotal,
            'uploadedCount' => $uploadedRequiredCount,
        ]);
    }

    public function upload(UploadDocumentRequest $request, MasterDocumentType $documentType)
    {
        $id = $this->getSessionRegistrationId();
        if (! $id) {
            return $this->redirectToAccess();
        }

        if (! $documentType->is_active) {
            abort(404);
        }

        $registration = TrbRegistration::findOrFail($id);

        $document = $this->docService->upsertDocument(
            $registration,
            $documentType,
            $request->file('file')
        );

        // 3. JALANKAN OCR (Saklar Otomatis)
        // Pastikan docService->upsertDocument mengembalikan object Model TrbDocument
        if ($document) {
            ProcessDocumentOCR::dispatch($document); 
        }

        return back()->with('status', 'Dokumen berhasil diunggah: ' . $documentType->name);
    }
}
