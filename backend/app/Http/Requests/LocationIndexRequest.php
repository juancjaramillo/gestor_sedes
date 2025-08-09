<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationIndexRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->has('name') ? trim((string) $this->input('name')) : null,
            'code' => $this->has('code') ? strtoupper(trim((string) $this->input('code'))) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'     => ['nullable','string','max:100'],
            'code'     => ['nullable','string','max:10'],
            'page'     => ['nullable','integer','min:1'],
            'per_page' => ['nullable','integer','min:1','max:100'],
        ];
    }
}
