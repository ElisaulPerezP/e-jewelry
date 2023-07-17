<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [

            'name' => [
                'string',
                'required',
                ],
            'guardApi' => [
                'required',
                Rule::in(['true', 'false']),
            ],
            'guardWeb' => [
                'required',
                Rule::in(['true', 'false']),
            ],
        ];
    }
}
