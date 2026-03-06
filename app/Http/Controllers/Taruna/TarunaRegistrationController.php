<?php

namespace App\Http\Controllers\Taruna;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taruna\StoreRegistrationRequest;
use App\Http\Requests\Taruna\UpdateIdentityRequest;
use App\Http\Requests\Taruna\VerifyEditAccessRequest;
use App\Models\MasterDocumentType;
use App\Models\TrbRegistration;
use App\Services\TarunaRegistrationService;
use Illuminate\Http\Request;

class TarunaRegistrationController extends Controller
{
    public function __construct(private TarunaRegistrationService $service) {}

    public function create()
    {
        $documentTypes = MasterDocumentType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('taruna-registration', compact('documentTypes'));
    }

    public function store(StoreRegistrationRequest $request)
    {
        [$registration, $rawToken] = $this->service->createRegistration($request->validated());

        session(['taruna_registration_id' => $registration->id]);

        return redirect()
            ->route('taruna.success')
            ->with('edit_token', $rawToken)
            ->with('edit_token_expires_at', optional($registration->edit_token_expires_at)->toDateTimeString())
            ->with('kode_pelaut', $registration->kode_pelaut);
    }

    public function success()
    {
        return view('taruna-success', [
            'token' => session('edit_token'),
            'expiresAt' => session('edit_token_expires_at'),
            'kodePelaut' => session('kode_pelaut'),
        ]);
    }

    public function editAccessForm()
    {
        return view('taruna-edit-access');
    }

    public function editAccessSubmit(VerifyEditAccessRequest $request)
    {
        $data = $request->validated();

        $registration = $this->service->verifyEditAccess($data['kode_pelaut'], $data['token']);

        session(['taruna_registration_id' => $registration->id]);

        session(['edit_registration_id' => $registration->id]);

        return redirect()->route('taruna.edit.form');
    }

    public function editForm()
    {
        $id = session('taruna_registration_id');
        abort_unless($id, 403, 'Akses edit belum diverifikasi.');

        $registration = TrbRegistration::findOrFail($id);

        return view('taruna-edit-form', compact('registration'));
    }

    public function update(UpdateIdentityRequest $request)
    {
        $id = session('taruna_registration_id');
        abort_unless($id, 403, 'Akses edit belum diverifikasi.');

        $registration = TrbRegistration::findOrFail($id);

        $this->service->updateIdentity($registration, $request->validated());

        return redirect()
            ->route('taruna.edit.form')
            ->with('status', 'Data berhasil diperbarui.');
    }
}
