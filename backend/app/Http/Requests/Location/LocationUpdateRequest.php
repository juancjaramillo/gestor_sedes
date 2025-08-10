<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $id = $this->route('location')?->id ?? null;

        return [
            'code'  => ['sometimes','string','max:10', Rule::unique('locations', 'code')->ignore($id)],
            'name'  => ['sometimes','string','max:100'],
            'image' => ['sometimes','file','image','max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => $this->has('code') ? strtoupper(trim((string) $this->get('code'))) : $this->get('code'),
            'name' => $this->has('name') ? trim((string) $this->get('name')) : $this->get('name'),
        ]);
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'code.string' => 'El código debe ser texto.',
            'code.max'    => 'El código no puede tener más de 10 caracteres.',
            'code.unique' => 'Ya existe una sede con ese código.',

            'name.string' => 'El nombre debe ser texto.',
            'name.max'    => 'El nombre no puede tener más de 100 caracteres.',

            'image.file'  => 'La imagen no es un archivo válido.',
            'image.image' => 'La imagen debe ser de tipo imagen.',
            'image.max'   => 'La imagen no puede superar 2 MB.',
        ];
    }
}
