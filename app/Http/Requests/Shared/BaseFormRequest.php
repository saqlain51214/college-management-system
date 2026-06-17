<?php

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        $path = resource_path('json/validation-messages.json');

        if (! file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true)['messages'] ?? [];
    }
}
