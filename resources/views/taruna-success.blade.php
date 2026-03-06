@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-elev">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h3 class="section-title mb-1">Pendaftaran berhasil</h3>
                            <div class="muted">Simpan token ini untuk edit identitas jika ada kesalahan.</div>
                        </div>
                        <span class="badge text-bg-success align-self-start">Sukses</span>
                    </div>

                    @if (!$token)
                        <div class="alert alert-warning mb-0">
                            Token tidak tersedia di halaman ini. Biasanya karena halaman direfresh.
                            Jika token hilang, solusi paling aman adalah minta admin untuk regenerasi token.
                        </div>
                    @else
                        <div class="mb-3">
                            <div><span class="fw-semibold">Kode Pelaut:</span> {{ $kodePelaut }}</div>
                            <div><span class="fw-semibold">Berlaku sampai:</span> {{ $expiresAt }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Token Edit</label>
                            <div class="input-group">
                                <input id="tokenBox" class="form-control input-h" value="{{ $token }}" readonly>
                                <button class="btn btn-outline-primary" type="button" onclick="copyToken()">Salin</button>
                            </div>
                            <div class="muted small mt-2">Simpan di tempat aman. Jangan dibagikan ke orang lain.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <a class="btn btn-primary btn-pill" href="{{ route('taruna.edit.access') }}">Saya ingin edit
                                identitas</a>
                            <a class="btn btn-success btn-pill" href="{{ route('taruna.docs.index') }}">
                                Lanjut Upload Dokumen
                            </a>
                            <a class="btn btn-outline-secondary btn-pill" href="{{ route('taruna.register') }}">Kembali ke
                                halaman pendaftaran</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToken() {
            const el = document.getElementById('tokenBox');
            el.select();
            el.setSelectionRange(0, 99999);
            document.execCommand('copy');
        }
    </script>
@endpush
