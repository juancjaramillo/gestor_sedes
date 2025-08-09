<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:100'],
            'code'     => ['sometimes', 'string', 'max:20'],
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->has('name') ? trim($this->get('name')) : null,
            'code' => $this->has('code') ? strtoupper(trim($this->get('code'))) : null,
        ]);
    }
}
