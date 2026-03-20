@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-elev">
            <div class="card-body p-4 p-lg-5">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h3 class="section-title mb-1">Upload Dokumen TRB</h3>
                        <div class="muted">Upload dokumen satu per satu sesuai ketentuan.</div>
                    </div>
                    <span class="badge text-bg-primary align-self-start fs-6 px-3 py-2">
                        {{ $uploadedCount }} / {{ $requiredTotal }} wajib terunggah
                    </span>
                </div>

                {{-- Flash Message --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-1">
                            <i class="bi bi-x-circle-fill me-1"></i> Upload gagal:
                        </div>
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
                            $acceptAttr  = count($allowedArr) ? implode(',', $allowedArr) : '';

                            // Status flags
                            $sysStatus   = $isUploaded ? $doc->system_validation_status : null;
                            $adminStatus = $isUploaded ? $doc->admin_verification_status : null;
                            $ocrConf     = $isUploaded ? (float) $doc->ocr_confidence : null;

                            // Fix: cek null dulu sebelum compare angka
                            $isLowConfidence = $isUploaded
                                && $ocrConf !== null
                                && $ocrConf < 70
                                && $doc->ocr_status === 'processed';

                            // Dokumen sudah approved — sembunyikan form upload
                            $isApproved = $adminStatus === 'approved';

                            // Warna border card berdasarkan status keseluruhan
                            $borderClass = 'border';
                            if ($isApproved)                          $borderClass = 'border border-success border-2';
                            elseif ($adminStatus === 'rejected')      $borderClass = 'border border-danger border-2';
                            elseif ($sysStatus === 'failed')          $borderClass = 'border border-danger';
                            elseif ($sysStatus === 'passed')          $borderClass = 'border border-warning';
                            elseif ($isUploaded)                      $borderClass = 'border border-primary';
                        @endphp

                        <div class="col-12">
                            <div class="{{ $borderClass }} rounded-4 p-3 bg-white">

                                {{-- Header Card: Nama Dokumen + Badge --}}
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold d-flex align-items-center gap-2 flex-wrap">
                                            <i class="bi bi-file-earmark-text"></i>
                                            {{ $type->name }}
                                            @if ($type->is_required)
                                                <span class="badge text-bg-danger">Wajib</span>
                                            @else
                                                <span class="badge text-bg-secondary">Opsional</span>
                                            @endif
                                        </div>
                                        <div class="muted small mt-1">
                                            Format: {{ $allowedText }} &bull; Maks: {{ $type->max_size_mb }} MB
                                        </div>
                                    </div>

                                    {{-- Badge Status Keseluruhan --}}
                                    @if (!$isUploaded)
                                        <span class="badge text-bg-light text-dark border align-self-start">
                                            <i class="bi bi-dash-circle me-1"></i>Belum Diunggah
                                        </span>
                                    @elseif ($isApproved)
                                        <span class="badge text-bg-success align-self-start">
                                            <i class="bi bi-patch-check-fill me-1"></i>Disetujui Admin
                                        </span>
                                    @elseif ($adminStatus === 'rejected')
                                        <span class="badge text-bg-danger align-self-start">
                                            <i class="bi bi-x-circle-fill me-1"></i>Ditolak Admin
                                        </span>
                                    @elseif ($sysStatus === 'failed')
                                        <span class="badge text-bg-danger align-self-start">
                                            <i class="bi bi-x-circle me-1"></i>Gagal Validasi
                                        </span>
                                    @elseif ($sysStatus === 'passed')
                                        <span class="badge text-bg-warning align-self-start">
                                            <i class="bi bi-hourglass-split me-1"></i>Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="badge text-bg-primary align-self-start">
                                            <i class="bi bi-arrow-repeat me-1"></i>Sedang Diproses
                                        </span>
                                    @endif
                                </div>

                                {{-- Detail Dokumen (jika sudah upload) --}}
                                @if ($isUploaded)

                                    {{-- Catatan penolakan dari Admin --}}
                                    @if ($adminStatus === 'rejected' && $doc->admin_verification_note)
                                        <div class="alert alert-danger mt-3 mb-2 py-2 px-3 small">
                                            <div class="fw-bold mb-1">
                                                <i class="bi bi-megaphone-fill me-1"></i>Catatan dari Admin:
                                            </div>
                                            {{ $doc->admin_verification_note }}
                                        </div>
                                    @endif

                                    {{-- Pesan gagal validasi sistem --}}
                                    @if ($sysStatus === 'failed' && $adminStatus !== 'rejected')
                                        <div class="alert alert-danger mt-3 mb-2 py-2 px-3 small">
                                            <div class="fw-bold">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Validasi Gagal:
                                            </div>
                                            {{ $doc->system_validation_message }}
                                        </div>
                                    @endif

                                    {{-- Peringatan skor OCR rendah --}}
                                    @if ($isLowConfidence && $sysStatus !== 'failed')
                                        <div class="alert alert-warning mt-3 mb-2 py-2 px-3 small">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Kualitas scan dokumen kurang jelas (Skor: {{ round($ocrConf, 0) }}%).
                                            Jika ditolak admin, mohon upload ulang dengan kualitas lebih baik.
                                        </div>
                                    @endif

                                    {{-- Info Status 3 Baris --}}
                                    <div class="mt-3 d-flex flex-wrap gap-2">
                                        {{-- Validasi Sistem --}}
                                        <span class="badge rounded-pill
                                            {{ $sysStatus === 'passed' ? 'text-bg-success' : ($sysStatus === 'failed' ? 'text-bg-danger' : 'text-bg-secondary') }}">
                                            Sistem:
                                            {{ $sysStatus === 'passed' ? '✓ Lolos' : ($sysStatus === 'failed' ? '✗ Gagal' : '⏳ Proses') }}
                                        </span>

                                        {{-- Skor OCR --}}
                                        @if ($ocrConf !== null)
                                            <span class="badge rounded-pill
                                                {{ $ocrConf >= 70 ? 'text-bg-success' : ($ocrConf >= 50 ? 'text-bg-warning' : 'text-bg-danger') }}">
                                                OCR: {{ round($ocrConf, 0) }}%
                                            </span>
                                        @endif

                                        {{-- Verifikasi Admin --}}
                                        <span class="badge rounded-pill
                                            {{ $adminStatus === 'approved' ? 'text-bg-success' : ($adminStatus === 'rejected' ? 'text-bg-danger' : 'text-bg-secondary') }}">
                                            Admin:
                                            {{ $adminStatus === 'approved' ? '✓ Disetujui' : ($adminStatus === 'rejected' ? '✗ Ditolak' : '⏳ Menunggu') }}
                                        </span>

                                        {{-- Link Dokumen --}}
                                        @if ($doc->drive_view_url)
                                            <a href="{{ $doc->drive_view_url }}"
                                               target="_blank" rel="noopener"
                                               class="badge rounded-pill text-bg-primary text-decoration-none">
                                                <i class="bi bi-eye me-1"></i>Lihat Dokumen
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Waktu upload --}}
                                    <div class="muted small mt-2">
                                        <i class="bi bi-clock me-1"></i>
                                        Diunggah: {{ optional($doc->uploaded_at)->format('d M Y, H:i') }}
                                    </div>

                                @endif
                                {{-- End Detail Dokumen --}}

                                {{-- Form Upload --}}
                                {{-- Sembunyikan form jika sudah approved --}}
                                @if (!$isApproved)
                                    <form class="mt-3" method="POST"
                                          action="{{ route('taruna.docs.upload', $type) }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-9">
                                                <input class="form-control
                                                    {{ ($isUploaded && ($sysStatus === 'failed' || $adminStatus === 'rejected')) ? 'is-invalid' : '' }}"
                                                       type="file" name="file" required
                                                       @if($acceptAttr) accept="{{ $acceptAttr }}" @endif>
                                                <div class="muted small mt-1">
                                                    @if ($isUploaded && ($sysStatus === 'failed' || $adminStatus === 'rejected'))
                                                        <span class="text-danger">
                                                            <i class="bi bi-arrow-repeat me-1"></i>
                                                            Mohon upload ulang dokumen yang lebih jelas.
                                                        </span>
                                                    @else
                                                        Upload ulang akan menggantikan dokumen sebelumnya.
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-grid">
                                                <button type="submit"
                                                    class="btn btn-pill
                                                        {{ ($isUploaded && ($sysStatus === 'failed' || $adminStatus === 'rejected'))
                                                            ? 'btn-danger'
                                                            : ($isUploaded ? 'btn-outline-primary' : 'btn-primary') }}">
                                                    <i class="bi bi-upload me-1"></i>
                                                    {{ $isUploaded ? 'Ganti File' : 'Upload' }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    {{-- Pesan jika sudah approved --}}
                                    <div class="mt-3 p-2 bg-success bg-opacity-10 rounded-3 small text-success">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Dokumen telah disetujui oleh admin. Upload ulang tidak diperlukan.
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Jika semua dokumen wajib sudah terunggah, admin akan melakukan verifikasi.
                    </div>
                    <a class="btn btn-outline-secondary btn-pill btn-sm"
                       href="{{ route('taruna.register') }}">
                        Kembali ke Pendaftaran
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection