<?php

namespace App\Http\Requests\Taruna;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_pelaut' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:150'],
            'tempat_lahir' => ['required', 'string', 'max:120'],
            'tanggal_lahir' => ['required', 'date'],
            'nik_ktp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'kelurahan_desa' => ['required', 'string', 'max:120'],
            'kecamatan' => ['required', 'string', 'max:120'],
            'kabupaten_kota' => ['required', 'string', 'max:120'],
            'jenis_kelamin' => ['required', 'string', 'max:20'],
            'nama_ibu_kandung' => ['required', 'string', 'max:150'],
            'no_whatsapp' => ['required', 'string', 'max:30'],
            'tahun_masuk_taruna' => ['required', 'integer'],
        ];
    }
}
