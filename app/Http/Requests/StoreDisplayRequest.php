<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDisplayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'orientation' => ['required', Rule::in(['landscape', 'portrait'])],
            'playlist_id' => ['nullable', 'exists:playlists,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'location' => 'lokasi',
            'orientation' => 'orientasi',
            'playlist_id' => 'playlist',
        ];
    }
}
