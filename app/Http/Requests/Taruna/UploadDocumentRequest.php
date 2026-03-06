<?php

namespace App\Http\Requests\Taruna;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized tCSXCXSo make this request.
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
        $documentType = $this->route('documentType'); // route model binding MasterDocumentType
        $allowed = is_array($documentType?->allowed_mime_types) ? $documentType->allowed_mime_types : [];
        $maxKb = (int) ($documentType?->max_size_mb ?? 10) * 1024;
        $maxKb = $maxKb * 1024;
        $rules = [
            'file' => ['required', 'file', 'max:' . $maxKb],
        ];
        // Supaya tidak error kalau allowed kosong
        if (count($allowed) > 0) {
            $rules['file'][] = 'mimetypes:' . implode(',', $allowed);
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'file.mimetypes' => 'Tipe file tidak sesuai dengan ketentuan dokumen ini.',
            'file.max' => 'Ukuran file melebihi batas yang ditentukan.',
        ];
    }
}
