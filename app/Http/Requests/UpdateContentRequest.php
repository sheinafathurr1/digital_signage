<?php

namespace App\Http\Requests;

use App\Models\Content;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type');
        $hasExistingFile = filled($this->route('content')?->file_path);

        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['image', 'video', 'text', 'html-embed'])],
            'file' => [
                Rule::requiredIf(($type === 'image' || $type === 'video') && ! $hasExistingFile),
                'nullable',
                $type === 'video' ? 'mimes:mp4' : 'image',
                $type === 'video' ? 'max:51200' : 'max:5120',
            ],
            'text_body' => [Rule::requiredIf($type === 'text' || $type === 'html-embed'), 'nullable', 'string'],
            'background_color' => ['nullable', Rule::in(array_keys(Content::BACKGROUND_COLORS))],
            'duration' => ['required', 'integer', 'min:1', 'max:3600'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_priority' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'type' => 'tipe',
            'file' => 'berkas',
            'text_body' => 'isi teks',
            'background_color' => 'warna latar',
            'duration' => 'durasi',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'order' => 'urutan',
            'is_active' => 'status aktif',
            'is_priority' => 'prioritas',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Berkas wajib diunggah untuk tipe gambar/video.',
            'file.image' => 'Berkas harus berupa gambar (jpg, jpeg, png).',
            'file.mimes' => 'Berkas video harus berformat mp4.',
            'text_body.required' => 'Isi teks/HTML wajib diisi untuk tipe ini.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];
    }
}
