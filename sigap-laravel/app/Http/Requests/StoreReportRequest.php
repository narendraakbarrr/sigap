<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:10',
            'category_id'      => 'required|exists:report_categories,id',
            'location_address' => 'required|string|max:500',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'            => 'Judul laporan wajib diisi.',
            'description.min'           => 'Deskripsi minimal 10 karakter.',
            'category_id.exists'        => 'Kategori tidak valid.',
            'location_address.required' => 'Alamat lokasi wajib diisi.',
            'photo.image'               => 'File harus berupa gambar.',
            'photo.max'                 => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
