@extends('layouts.app')

@section('title', 'Akses Edit Identitas')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-elev">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4">
                        <h3 class="section-title mb-1">Akses Edit Identitas</h3>
                        <div class="muted">Masukkan kode pelaut dan token untuk mengakses form edit.</div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('taruna.edit.access.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label required">Kode Pelaut</label>
                            <input class="form-control input-h" name="kode_pelaut" value="{{ old('kode_pelaut') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Token</label>
                            <input class="form-control input-h" name="token" value="{{ old('token') }}" required>
                        </div>

                        <button class="btn btn-success btn-pill w-100" type="submit">Masuk Edit</button>
                        <br>
                        <br>
                    </form>
                    <div class="d-grid gap-2">
                        <a class="btn btn-primary btn-pill" href="{{ route('taruna.docs.index') }}">
                            Lanjut Upload Dokumen
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
