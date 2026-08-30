<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaylistContentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_ids' => ['array'],
            'content_ids.*' => ['integer', 'exists:contents,id'],
        ];
    }
}
