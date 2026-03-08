@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-elev">
            <div class="card-body p-4 p-lg-5">

                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h3 class="section-title mb-1">Upload Dokumen TRB</h3>
                        <div class="muted">Upload dokumen satu per satu sesuai ketentuan.</div>
                    </div>
                    <span class="badge text-bg-primary align-self-start">
                        {{ $uploadedCount }} / {{ $requiredTotal }} wajib terunggah
                    </span>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-1">Upload gagal:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    @foreach ($types as $type)
                        @php
                            $doc = $docsByType->get($type->id);
                            $isUploaded = !is_null($doc);

                            $allowedArr = is_array($type->allowed_mime_types) ? $type->allowed_mime_types : [];
                            $allowedText = count($allowedArr) ? implode(', ', $allowedArr) : '-';
                            $acceptAttr = count($allowedArr) ? implode(',', $allowedArr) : '';
                            
                            // Logika pendeteksi skor rendah
                            $isLowConfidence = $isUploaded && $doc->ocr_confidence < 70 && $doc->ocr_status !== 'failed';
                        @endphp

                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold d-flex align-items-center gap-2">
                                            {{ $type->name }}
                                            @if ($type->is_required)
                                                <span class="badge text-bg-danger">Wajib</span>
                                            @else
                                                <span class="badge text-bg-secondary">Opsional</span>
                                            @endif
                                        </div>

                                        <div class="muted small">
                                            Tipe file: {{ $allowedText }} • Maks: {{ $type->max_size_mb }} MB
                                            @if ($type->ocr_enabled)
                                                • OCR: aktif
                                            @endif
                                        </div>
                                    </div>

                                    @if ($isUploaded)
                                        @if($doc->system_validation_status == 'failed')
                                            <span class="badge text-bg-danger">Gagal Validasi</span>
                                        @else
                                            <span class="badge text-bg-success">Sudah Upload</span>
                                        @endif
                                    @else
                                        <span class="badge text-bg-light text-dark border">Belum</span>
                                    @endif
                                </div>

                                @if ($isUploaded)
                                    {{-- Penambahan Alert Box untuk hasil scan kabur/failed --}}
                                    @if($doc->system_validation_status == 'failed' || $isLowConfidence)
                                        <div class="alert {{ $doc->system_validation_status == 'failed' ? 'alert-danger' : 'alert-warning' }} mt-2 mb-2 p-2 small">
                                            <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Perhatian:</div>
                                            {{ $doc->system_validation_message }}
                                            @if($isLowConfidence)
                                                (Skor: {{ round($doc->ocr_confidence, 0) }}%). Dokumen mungkin terbaca kurang jelas.
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-2 small">
                                        <div><span class="fw-semibold">Uploaded:</span> {{ optional($doc->uploaded_at)->toDateTimeString() }}</div>
                                        <div>
                                            <span class="fw-semibold">Link Dokumen:</span>
                                            <a href="{{ $doc->drive_view_url }}" target="_blank" rel="noopener">Buka</a>
                                        </div>
                                        <div class="muted">
                                            Sistem: <span class="{{ $doc->system_validation_status == 'failed' ? 'text-danger fw-bold' : '' }}">{{ strtoupper($doc->system_validation_status) }}</span>
                                            • OCR: {{ $doc->ocr_status }} ({{ round($doc->ocr_confidence, 0) }}%)
                                            • Admin: {{ $doc->admin_verification_status }}
                                        </div>
                                    </div>
                                @endif

                                <form class="mt-3" method="POST"
                                      action="{{ route('taruna.docs.upload', $type) }}"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-9">
                                            <input class="form-control {{ ($isUploaded && $doc->system_validation_status == 'failed') ? 'is-invalid' : '' }}" 
                                                   type="file" name="file" required
                                                   @if($acceptAttr) accept="{{ $acceptAttr }}" @endif>
                                            <div class="muted small mt-1">
                                                @if($isUploaded && $doc->system_validation_status == 'failed')
                                                    <span class="text-danger">Mohon upload ulang foto yang lebih jelas/terang.</span>
                                                @else
                                                    Upload ulang akan menggantikan dokumen sebelumnya untuk jenis ini.
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3 d-grid">
                                            <button class="btn {{ ($isUploaded && $doc->system_validation_status == 'failed') ? 'btn-danger' : 'btn-outline-primary' }} btn-pill" type="submit">
                                                {{ $isUploaded ? 'Ganti File' : 'Upload' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="muted">
                        Jika semua dokumen wajib sudah terunggah, admin akan melakukan verifikasi.
                    </div>
                    <a class="btn btn-outline-secondary btn-pill" href="{{ route('taruna.register') }}">
                        Kembali ke Pendaftaran
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection