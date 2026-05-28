<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'is_public' => ['sometimes', 'boolean'],
            'tags'      => ['sometimes', 'nullable', 'array'],
            'tags.*'    => ['string', 'max:50'],
        ];
    }
}
