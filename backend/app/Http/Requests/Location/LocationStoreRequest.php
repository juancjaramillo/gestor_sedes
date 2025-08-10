<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'code'  => ['required','string','max:10','unique:locations,code'],
            'name'  => ['required','string','max:100'],
            'image' => ['sometimes','file','image','max:2048'], // 2MB
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->get('code'))),
            'name' => trim((string) $this->get('name')),
        ]);
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'code.required' => 'El código es obligatorio.',
            'code.string'   => 'El código debe ser texto.',
            'code.max'      => 'El código no puede tener más de 10 caracteres.',
            'code.unique'   => 'Ya existe una sede con ese código.',

            'name.required' => 'El nombre es obligatorio.',
            'name.string'   => 'El nombre debe ser texto.',
            'name.max'      => 'El nombre no puede tener más de 100 caracteres.',

            'image.file'    => 'La imagen no es un archivo válido.',
            'image.image'   => 'La imagen debe ser de tipo imagen (jpg, png, etc.).',
            'image.max'     => 'La imagen no puede superar 2 MB.',
        ];
    }
}
