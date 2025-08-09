<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'code'  => ['required', 'string', 'max:20', 'unique:locations,code'],
            'name'  => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->get('code'))),
            'name' => trim((string) $this->get('name')),
        ]);
    }

    public function authorize(): bool { return true; }
}
