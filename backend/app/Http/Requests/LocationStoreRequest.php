<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $image = $this->input('image');
        $this->merge([
            'code'  => strtoupper(trim((string) $this->input('code'))),
            'name'  => trim(strip_tags((string) $this->input('name'))),
            'image' => $image === '' ? null : $image,
        ]);
    }

    public function rules(): array
    {
        return [
            'code'  => ['required','string','max:10','unique:locations,code'],
            'name'  => ['required','string','max:100'],
            'image' => ['nullable','string','max:255'],
        ];
    }
}
