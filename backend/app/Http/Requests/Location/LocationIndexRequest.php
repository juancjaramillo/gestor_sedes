<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
  /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name'     => ['sometimes','string','min:2','max:100'],
            'code'     => ['sometimes','string','max:20'],
            'per_page' => ['sometimes','integer','min:1','max:100'],
            'page'     => ['sometimes','integer','min:1'],
        ];
    }
}
