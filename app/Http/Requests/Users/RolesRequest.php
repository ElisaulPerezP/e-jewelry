<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'params.roles' => [
                'array',
            ],
            'params.roles.*' => [
                'integer',
                Rule::exists('roles', 'id'),
                'nullable',
            ],
        ];
    }
}
