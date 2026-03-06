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
                                        <span class="badge text-bg-success">Sudah Upload</span>
                                    @else
                                        <span class="badge text-bg-light text-dark border">Belum</span>
                                    @endif
                                </div>

                                @if ($isUploaded)
                                    <div class="mt-2 small">
                                        <div><span class="fw-semibold">Uploaded:</span> {{ optional($doc->uploaded_at)->toDateTimeString() }}</div>
                                        <div>
                                            <span class="fw-semibold">Link Dokumen:</span>
                                            <a href="{{ $doc->drive_view_url }}" target="_blank" rel="noopener">Buka</a>
                                        </div>
                                        <div class="muted">
                                            Sistem: {{ $doc->system_validation_status }}
                                            • OCR: {{ $doc->ocr_status }}
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
                                            <input class="form-control" type="file" name="file" required
                                                   @if($acceptAttr) accept="{{ $acceptAttr }}" @endif>
                                            <div class="muted small mt-1">
                                                Upload ulang akan menggantikan dokumen sebelumnya untuk jenis ini.
                                            </div>
                                        </div>

                                        <div class="col-md-3 d-grid">
                                            <button class="btn btn-outline-primary btn-pill" type="submit">
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