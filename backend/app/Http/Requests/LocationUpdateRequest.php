<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Location;

class LocationUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Location|null $location */
        $location = $this->route('location'); 
        $id = $location?->id ?? (int) ($this->route('id') ?? 0);

        return [
            'code'  => ['required', 'string', 'max:10', Rule::unique('locations','code')->ignore($id)],
            'name'  => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
