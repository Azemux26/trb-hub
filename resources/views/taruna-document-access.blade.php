@extends('layouts.app')

@section('title', 'Akses Upload Dokumen')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-elev">
            <div class="card-body p-4 p-lg-5">
                <h3 class="section-title mb-1">Akses Upload Dokumen</h3>
                <div class="muted mb-4">Masukkan kode pelaut dan token untuk melanjutkan upload dokumen.</div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('taruna.docs.access.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label required">Kode Pelaut</label>
                        <input class="form-control input-h" name="kode_pelaut" value="{{ old('kode_pelaut') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Token</label>
                        <input class="form-control input-h" name="token" value="{{ old('token') }}" required>
                    </div>

                    <button class="btn btn-success btn-pill w-100" type="submit">Lanjut Upload</button>
                </form>

                <div class="muted small mt-3">
                    Jika token hilang atau kedaluwarsa, silakan hubungi admin untuk regenerasi token.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection