<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'params.permissions' => [
                'array',
            ],
            'params.permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id'),
                'nullable',
            ],
        ];
    }
}
