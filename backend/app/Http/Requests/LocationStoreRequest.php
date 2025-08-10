<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LocationStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code'  => ['required', 'string', 'max:10', 'unique:locations,code'],
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
