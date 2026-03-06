@extends('layouts.app')

@section('title', 'Edit Identitas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-elev">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                    <div>
                        <h3 class="section-title mb-1">Edit Identitas Taruna</h3>
                        <div class="muted">Perbaiki identitas jika ada kesalahan. Dokumen tidak diedit di sini.</div>
                    </div>
                    <span class="badge text-bg-dark align-self-start">Edit</span>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('taruna.edit.update') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Kode Pelaut</label>
                            <input class="form-control input-h" name="kode_pelaut" value="{{ old('kode_pelaut', $registration->kode_pelaut) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Nama Lengkap</label>
                            <input class="form-control input-h" name="nama" value="{{ old('nama', $registration->nama) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Tempat Lahir</label>
                            <input class="form-control input-h" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Tanggal Lahir</label>
                            <input type="date" class="form-control input-h" name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', optional($registration->tanggal_lahir)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Nomor KTP</label>
                            <input class="form-control input-h" name="nik_ktp" value="{{ old('nik_ktp', $registration->nik_ktp) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Nomor WhatsApp</label>
                            <input class="form-control input-h" name="no_whatsapp" value="{{ old('no_whatsapp', $registration->no_whatsapp) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label required">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required>{{ old('alamat', $registration->alamat) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Kelurahan Desa</label>
                            <input class="form-control input-h" name="kelurahan_desa" value="{{ old('kelurahan_desa', $registration->kelurahan_desa) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Kecamatan</label>
                            <input class="form-control input-h" name="kecamatan" value="{{ old('kecamatan', $registration->kecamatan) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Kabupaten Kota</label>
                            <input class="form-control input-h" name="kabupaten_kota" value="{{ old('kabupaten_kota', $registration->kabupaten_kota) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Jenis Kelamin</label>
                            <select class="form-select input-h" name="jenis_kelamin" required>
                                <option value="L" @selected(old('jenis_kelamin', $registration->jenis_kelamin) === 'L')>Laki laki</option>
                                <option value="P" @selected(old('jenis_kelamin', $registration->jenis_kelamin) === 'P')>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Nama Ibu Kandung</label>
                            <input class="form-control input-h" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $registration->nama_ibu_kandung) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Tahun Masuk Taruna</label>
                            <input type="number" class="form-control input-h" name="tahun_masuk_taruna"
                                   value="{{ old('tahun_masuk_taruna', $registration->tahun_masuk_taruna) }}" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-success btn-pill w-100" type="submit">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection