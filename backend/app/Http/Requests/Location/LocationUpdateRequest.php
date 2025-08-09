<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('location')?->id;

        return [
            'code'  => ['required', 'string', 'max:20', Rule::unique('locations', 'code')->ignore($id)],
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

    public function authorize(): bool
    {
        return true;
    }
}
